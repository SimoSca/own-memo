using System;
using System.Net;
using System.Net.Sockets;
using System.Text;

class Program 
{
    static void Main(string[] args) 
    {
        Socket s = new Socket(AddressFamily.InterNetwork, SocketType.Dgram,
            ProtocolType.Udp);

        IPAddress broadcast = IPAddress.Parse("192.168.1.255");

        byte[] sendbuf = Encoding.ASCII.GetBytes(args[0]);
        IPEndPoint ep = new IPEndPoint(broadcast, 11001);

        s.SendTo(sendbuf, ep);

        Console.WriteLine("Message sent to the broadcast address");
    }
}

// C:\Windows\Microsoft.NET\Framework64\v4.0.30319\csc.exe udpSender.cs && udpSender.cs "Test now!"