# ALLIN1 — NexusTools Universal Converter Toolkit

A single dashboard that brings together multiple document, spreadsheet, image, and utility converters in one clean, modern web app. Built with PHP on the front end and powered by a custom Python API for the actual file-conversion logic, bridged together via JavaScript.

## ✨ Features

- **PDF ⇄ Word** — Extract text, layout, and formatting from PDFs into editable `.docx` files, and convert Word documents into professional, portable PDFs.
- **Spreadsheets (Excel ⇄ CSV)** — Convert data back and forth between Microsoft Excel (`.xlsx`) and generic CSV formats.
- **Image Converter** — Convert images between PNG, JPG, WEBP, GIF, and BMP formats instantly.
- **Image to Text (OCR)** — Extract text from images with high precision and copy it straight to the clipboard.
- **QR Code Generator** — Instantly create customizable QR codes for links, text, or contact info.
- **Unified Dashboard UI** — A single glassmorphic, card-based homepage that links out to every tool.

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Front End | PHP (page structure/routing), HTML5, CSS3 |
| Conversion Engine | Custom Python API — handles the actual document/image/spreadsheet conversion logic |
| Bridge Layer | JavaScript — connects the PHP front end to the Python API (AJAX/fetch calls) |
| UI/Styling | Custom CSS (Outfit font, glassmorphism/blur effects, gradient accents), Font Awesome icons |

## 🏗️ Architecture

```
Browser (PHP-rendered UI)
        │
        │  User selects a tool & uploads a file
        ▼
   JavaScript (fetch/AJAX)
        │
        │  Sends file/request to the conversion endpoint
        ▼
   Custom Python API
        │
        │  Performs the actual conversion (PDF↔Word, Excel↔CSV,
        │  image format conversion, OCR, QR generation, etc.)
        ▼
   Response returned to JS → rendered/downloaded in the browser
```

The PHP layer is responsible for page structure, routing between tools, and the overall UI shell. All heavy-lifting conversion work is offloaded to a separate Python API service, which JavaScript communicates with asynchronously so the page never needs to fully reload during a conversion.

## 📁 Project Structure

```
├── Convert/                 # Shared/core conversion utilities or landing logic
├── ExcelConverter/
│   └── index.php             # Excel ⇄ CSV tool
├── ImageConverter/
│   └── index.php             # Image format converter (PNG/JPG/WEBP/GIF/BMP)
├── ImageToText/
│   └── index.php             # OCR tool
├── PdfToWord/
│   └── index.php             # PDF → Word converter
├── WordToPdf/
│   └── index.php             # Word → PDF converter
├── qr pages/
│   └── qr.php                 # QR code generator
├── contact.php               # Feedback page
├── faq.php                   # Q&A page
├── index.php                 # Main dashboard / homepage
└── README.md
```

> Each tool folder contains its own `index.php` (and any supporting JS) that talks to the Python API for that specific conversion type.

## 🚀 Getting Started

### Prerequisites
- PHP server (e.g. XAMPP, MAMP, WAMP, or `php -S`)
- Python environment running the custom conversion API (with its required dependencies for PDF/Word/Excel/image/OCR processing)

### Run Locally
1. Start the Python API service so the conversion endpoints are reachable.
2. Point the JavaScript fetch calls in each tool to your local API URL (update config/endpoint as needed).
3. Serve the PHP front end:
   ```bash
   php -S localhost:8000
   ```
4. Open `http://localhost:8000/index.php` in your browser and pick a tool.

## 🔌 API Integration Notes

- The Python API exposes conversion endpoints consumed by the front end's JavaScript.
- File uploads are sent from the browser via JavaScript to the Python API; the converted result is returned and served back to the user (download/preview).
- Update API base URLs/keys/config in the JS files as appropriate for your environment (local vs. production).

## 📄 License

This project is for personal/portfolio/educational use.

## 🙌 Credits

- Development: *Your Name*
- Front End: PHP, HTML, CSS, JavaScript
- Conversion Engine: Custom Python API
