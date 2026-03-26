import subprocess
import sys
import os
import argparse

def convert_to_mp3(input_file, output_file):
    """
    Converts video (MP4) to Audio (MP3) and saves to output_file.
    """
    if not os.path.exists(input_file):
        sys.stderr.write(f"Error: Input file '{input_file}' not found.\n")
        sys.exit(1)

    command = [
        'ffmpeg',
        '-y',
        '-loglevel', 'error',
        '-i', input_file,
        '-vn',              # No video
        '-acodec', 'libmp3lame',
        '-q:a', '2',        # High quality
        '-f', 'mp3',        # Force format
        output_file
    ]

    try:
        process = subprocess.Popen(command, stderr=sys.stderr)
        process.wait()

        if process.returncode != 0:
            sys.stderr.write("FFmpeg returned error code.\n")
            if os.path.exists(output_file):
                os.remove(output_file)
            sys.exit(1)
        
        if not os.path.exists(output_file):
             sys.stderr.write("Error: Output file was not created.\n")
             sys.exit(1)

    except Exception as e:
        sys.stderr.write(f"Error: {str(e)}\n")
        if os.path.exists(output_file):
            os.remove(output_file)
        sys.exit(1)

def convert_to_mp4(input_file, output_file):
    """
    Converts Audio (MP3) to Video (MP4) and saves to output_file.
    """
    if not os.path.exists(input_file):
        sys.stderr.write(f"Error: Input file '{input_file}' not found.\n")
        sys.exit(1)

    command = [
        'ffmpeg',
        '-y',
        '-loglevel', 'error',
        '-f', 'lavfi', '-i', 'color=c=black:s=1280x720:r=1', # Black video, 1 fps
        '-i', input_file,
        '-c:v', 'libx264',
        '-tune', 'stillimage',
        '-shortest',
        '-c:a', 'aac',
        '-b:a', '192k',
        '-f', 'mp4',
        '-movflags', '+faststart', 
        output_file
    ]

    try:
        process = subprocess.Popen(command, stderr=sys.stderr)
        process.wait()

        if process.returncode != 0:
            sys.stderr.write("FFmpeg returned error code.\n")
            if os.path.exists(output_file):
                os.remove(output_file)
            sys.exit(1)

        if not os.path.exists(output_file):
             sys.stderr.write("Error: Output file was not created.\n")
             sys.exit(1)

    except Exception as e:
        sys.stderr.write(f"Error: {str(e)}\n")
        if os.path.exists(output_file):
            os.remove(output_file)
        sys.exit(1)

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Convert media files.")
    parser.add_argument("input_file", help="Path to input file")
    parser.add_argument("output_file", help="Path to output file")
    parser.add_argument("--to-mp3", action="store_true", help="Convert input to MP3")
    parser.add_argument("--to-mp4", action="store_true", help="Convert input to MP4")

    args = parser.parse_args()

    if args.to_mp3:
        convert_to_mp3(args.input_file, args.output_file)
    elif args.to_mp4:
        convert_to_mp4(args.input_file, args.output_file)
    else:
        sys.stderr.write("Error: Please specify conversion mode (--to-mp3 or --to-mp4)\n")
        sys.exit(1)
