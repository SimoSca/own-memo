require 'digest/sha1'
require 'base64'
require 'socket'


class WebSocketServer

  WS_MAGIC_STRING = "258EAFA5-E914-47DA-95CA-C5AB0DC85B11"

  def initialize(options={path: '/', port: 4567, host: 'localhost'})
    @path, port, host = options[:path], options[:port], options[:host]
    # Server bound to port ...
    @tcp_server = TCPServer.new(host, port)
  end

  # Returns a new WebSocketConnection to the client after handshake
  def accept
    # Wait for a client to connect
    socket = @tcp_server.accept
    send_handshake(socket) && WebSocketConnection.new(socket)
  end

  private

  def send_handshake(socket)
    # Read lines from socket
    request_line = socket.gets
    header = get_header(socket)
    if (request_line =~ /GET #{@path} HTTP\/1.1/) && (header =~ /Sec-WebSocket-Key: (.*)\r\n/)
      ws_accept = create_websocket_accept($1)
      send_handshake_response(socket, ws_accept)
      return true
    end
    send_400(socket)
    false
  end

  def get_header(socket, header = "")
    (line = socket.gets) == "\r\n" ? header : get_header(socket, header + line)
  end

  def send_400(socket)
    socket << "HTTP/1.1 400 Bad Request\r\n" +
              "Content-Type: text/plain\r\n" +
              "Connection: close\r\n" +
              "\r\n" +
              "Incorrect request"
    socket.close
  end

  def send_handshake_response(socket, ws_accept)
    socket << "HTTP/1.1 101 Switching Protocols\r\n" +
              "Upgrade: websocket\r\n" +
              "Connection: Upgrade\r\n" +
              "Sec-WebSocket-Accept: #{ws_accept}\r\n"
  end

  def create_websocket_accept(key)
    digest = Digest::SHA1.digest(key + WS_MAGIC_STRING)
    Base64.encode64(digest)
  end

end


class WebSocketConnection
  OPCODE_TEXT = 0x01

  attr_reader :socket

  def initialize(socket)
    @socket = socket
  end

  def recv
    fin_and_opcode = socket.read(1).bytes
    mask_and_length_indicator = socket.read(1).bytes[0]
    length_indicator = mask_and_length_indicator - 128

    length =  if length_indicator <= 125
                length_indicator
              elsif length_indicator == 126
                socket.read(2).unpack("n")[0]
              else
                socket.read(8).unpack("Q>")[0]
              end

    keys = socket.read(4).bytes
    encoded = socket.read(length).bytes

    decoded = encoded.each_with_index.map do |byte, index|
      byte ^ keys[index % 4]
    end

    decoded.pack("c*")
  end

  def send(message)
    bytes = [0x80 | OPCODE_TEXT]
    size = message.bytesize

    bytes +=  if size <= 125
                [size] # i.e. `size | 0x00`; if masked, would be `size | 0x80`, or size + 128
              elsif size < 2**16
                [126] + [size].pack("n").bytes
              else
                [127] + [size].pack("Q>").bytes
              end

    bytes += message.bytes
    data = bytes.pack("C*")
    socket << data
  end

end

# class WebSocketClient



server = WebSocketServer.new
listeners = []

# to each request to create a socket connection create an endependend Thread:
# if connection is raised, then each Thread realize an infinite while loop to remain in message listening
loop do
    # attend to socket request:
    #   accept realize the handshake,
    #   and if goes true then i can take the accepted socket connection win single client
    puts "WebSocketServer is listening for connections..."
    Thread.new(server.accept) do |connection|
        # only one time after accept
        puts "Connected"
        # store all user connections
        listeners.push(connection)
        # continously listen to message of a single user connection
        while (message = connection.recv)
            puts "Size of listeners: #{listeners.length}"
            # puts "Received #{message}"
            connection.send("Received #{message}. Thanks!")
            # the server replies to all user...
            # implementation change based on application engine (example adding a channel parameter)
            listeners.each { |single|
                # TODO:
                #   if possible, add check to delete closed connections from listeners arrat
                single.send("Reload")
            }
        end
    end
end
