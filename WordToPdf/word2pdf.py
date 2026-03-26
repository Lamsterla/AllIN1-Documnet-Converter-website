import sys
import os
import time

def convert_word_to_pdf(docx_path, pdf_path, progress_file=None):
    if not os.path.exists(docx_path):
        sys.stderr.write(f"Error: input file {docx_path} not found.\n")
        sys.exit(1)
        
    def write_prog(prog):
        if progress_file:
            try:
                with open(progress_file, 'w') as f:
                    f.write(str(prog))
            except:
                pass
                
    write_prog(10)
    
    try:
        from docx2pdf import convert
        # docx2pdf has no built-in progress callbacks we can easily hook into for a single file,
        # so we will just simulate some progress before the blocking call
        write_prog(40)
        
        convert(docx_path, pdf_path)
        
        write_prog(100)
    except Exception as e:
        sys.stderr.write(f"Conversion failed: {str(e)}\n")
        sys.exit(1)

if __name__ == "__main__":
    if len(sys.argv) < 3:
        sys.stderr.write("Usage: python word2pdf.py <input.docx> <output.pdf> [progress.txt]\n")
        sys.exit(1)
        
    input_docx = sys.argv[1]
    output_pdf = sys.argv[2]
    progress_file = sys.argv[3] if len(sys.argv) > 3 else None
    
    convert_word_to_pdf(input_docx, output_pdf, progress_file)
    
    if not os.path.exists(output_pdf):
        sys.stderr.write("Error: Output PDF file was not generated.\n")
        sys.exit(1)
