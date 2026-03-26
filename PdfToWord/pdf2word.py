import sys
import os
import logging
from pdf2docx import Converter

class ProgressFileHandler(logging.Handler):
    def __init__(self, filepath):
        super().__init__()
        self.filepath = filepath
        self.progress = 0
        
    def write_prog(self, prog):
        try:
            with open(self.filepath, 'w') as f:
                f.write(str(prog))
        except:
            pass

    def emit(self, record):
        msg = self.format(record)
        # Guess progress from pdf2docx standard INFO logs
        if "[1/4]" in msg: self.write_prog(10)
        elif "[2/4]" in msg: self.write_prog(25)
        elif "[3/4]" in msg: self.write_prog(50)
        elif "[4/4]" in msg: self.write_prog(90)

def convert_pdf_to_docx(pdf_path, docx_path, progress_file=None):
    if not os.path.exists(pdf_path):
        sys.stderr.write(f"Error: input file {pdf_path} not found.\n")
        sys.exit(1)
        
    if progress_file:
        logger = logging.getLogger("pdf2docx")
        logger.setLevel(logging.INFO)
        handler = ProgressFileHandler(progress_file)
        logger.addHandler(handler)
        handler.write_prog(5) # Give initial progress indicating python started
    
    try:
        # Create a PDF to DOCX converter instance
        cv = Converter(pdf_path)
        # Convert the PDF file to DOCX target
        cv.convert(docx_path)
        # Close the converter
        cv.close()
        
        if progress_file:
            with open(progress_file, 'w') as f:
                f.write("100")
    except Exception as e:
        sys.stderr.write(f"Conversion failed: {str(e)}\n")
        sys.exit(1)

if __name__ == "__main__":
    if len(sys.argv) < 3:
        sys.stderr.write("Usage: python pdf2word.py <input.pdf> <output.docx> [progress.txt]\n")
        sys.exit(1)
        
    input_pdf = sys.argv[1]
    output_docx = sys.argv[2]
    progress_file = sys.argv[3] if len(sys.argv) > 3 else None
    
    # Run the conversion
    convert_pdf_to_docx(input_pdf, output_docx, progress_file)
    
    # Verify the output was created
    if not os.path.exists(output_docx):
        sys.stderr.write("Error: Output DOCX file was not generated.\n")
        sys.exit(1)
