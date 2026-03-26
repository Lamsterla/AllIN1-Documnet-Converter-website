import sys
import time
import os
from PIL import Image

def update_progress(progress_file, percent):
    if progress_file:
        try:
            with open(progress_file, 'w') as f:
                f.write(str(percent))
        except:
            pass

def convert_image(input_path, output_path, out_format, progress_file):
    try:
        update_progress(progress_file, 10)
        time.sleep(0.5)

        if not os.path.exists(input_path):
            raise FileNotFoundError("Input file does not exist")

        img = Image.open(input_path)
        update_progress(progress_file, 40)
        
        save_format = out_format.upper()
        if save_format == 'JPG':
            save_format = 'JPEG'
             
        # Handle RGBA to RGB conversion for formats that don't support alpha
        if save_format in ['JPEG', 'BMP'] and img.mode in ('RGBA', 'LA', 'P'):
            # Create a white background
            background = Image.new('RGB', img.size, (255, 255, 255))
            if img.mode == 'P':
                img = img.convert('RGBA')
            
            # If the image has an alpha channel, use it as a mask
            if 'A' in img.mode:
                background.paste(img, mask=img.split()[-1])
            else:
                background.paste(img)
            img = background
        elif save_format in ['JPEG', 'BMP'] and img.mode != 'RGB':
            img = img.convert('RGB')
            
        update_progress(progress_file, 70)
        
        # Save the image
        # Exclude animation frames or special arguments dynamically based on format
        save_kwargs = {}
        if save_format == 'JPEG':
            save_kwargs['quality'] = 95
        elif save_format == 'GIF':
            # Handling saving animated GIFs might need save_all=True if we want, but sticking to single frame for simplicity.
            pass
            
        img.save(output_path, format=save_format, **save_kwargs)
        
        update_progress(progress_file, 100)
    except Exception as e:
        print(f"Error: {e}", file=sys.stderr)
        if progress_file and os.path.exists(progress_file):
            try:
                os.remove(progress_file)
            except:
                pass
        sys.exit(1)

if __name__ == "__main__":
    if len(sys.argv) < 5:
        print("Usage: python image_convert.py <input> <output> <format> <progress_file>", file=sys.stderr)
        sys.exit(1)

    input_path = sys.argv[1]
    output_path = sys.argv[2]
    out_format = sys.argv[3]
    progress_file = sys.argv[4] if len(sys.argv) >= 5 else None
    
    if out_format.startswith('--to-'):
        out_format = out_format.replace('--to-', '')

    convert_image(input_path, output_path, out_format, progress_file)
