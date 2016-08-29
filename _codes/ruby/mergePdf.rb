puts "Starting merge pdf"

pdfs = []
pdfOutput = "la_dieta_del_dottor_mozzi.pdf"

# order and collect pdfs
r = Regexp.new("\([0-9]+\).pdf")
Dir.glob('./*.pdf').sort_by {|s| v = s.scan(/(.*)\(([0-9]+)\).pdf/).flatten; v[1].to_i; }.each do |file|
	pdfs << File.realpath(file)
end

################################################################################################################
# Prawn e' stato rimosso perche' in realta' non funziona bene per il semplice merge dei pdf
# meglio utilizzarlo come editor pdf, per eventualmente intrecciarlo a "combine_pdf" per un lavoro molto fine
# Prawn part
# require 'prawn'
# count = 0
# Prawn::Document.generate(pdfOutput, {:page_size => 'A4', :skip_page_creation => true}) do |pdf|
#  	pdfs.each do |pdf_file|
#  		count += 1
#  		if File.exists?(pdf_file) && (count < 5)
#  			puts "\t generating by pdf #{pdf_file}"
#  			pdf_temp_nb_pages = Prawn::Document.new(:template => pdf_file).page_count
#  			(1..pdf_temp_nb_pages).each do |i|
#  				# puts "kk #{i}"
#  				pdf.start_new_page(:template => pdf_file, :template_page => i)
#  			end
#  		end
#  	end
# end
################################################################################################################

require "combine_pdf"

pdf = CombinePDF.new
r = Regexp.new(pdfOutput)
n_page = 0
pdfs.each do |single_pdf|
	if single_pdf.include?(pdfOutput)
		puts "Escludo l'output dalla lista..."
		next
	end
	puts "\t generating by pdf #{single_pdf}"
	CombinePDF.load(single_pdf).pages.each do |page|
  		n_page += 1
  		if n_page.even?
  			pdf << page.rotate_left.rotate_left # if i.even?
  		else
  			pdf << page
  		end
	end
end

pdf.save pdfOutput

