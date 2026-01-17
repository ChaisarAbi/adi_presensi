<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Response;

class BarcodeController extends Controller
{
    /**
     * Display barcode generator page
     */
    public function index()
    {
        $students = Student::all();
        return view('admin.barcode.index', compact('students'));
    }

    /**
     * Generate QR code for student
     */
    public function generate(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        
        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new ImagickImageBackEnd()
        );
        
        $writer = new Writer($renderer);
        
        // Data to encode in QR code
        $data = json_encode([
            'id' => $student->id,
            'nis' => $student->nis,
            'nama' => $student->nama,
            'barcode' => $student->barcode,
            'timestamp' => now()->toISOString()
        ]);
        
        $qrCode = $writer->writeString($data);
        
        return Response::make($qrCode, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'inline; filename="barcode-' . $student->nis . '.png"'
        ]);
    }

    /**
     * Print barcode for student
     */
    public function print($id)
    {
        $student = Student::with('classSchedule')->findOrFail($id);
        return view('admin.barcode.print', compact('student'));
    }

    /**
     * Print all barcodes
     */
    public function printAll()
    {
        $students = Student::with('classSchedule')->get();
        return view('admin.barcode.print-all', compact('students'));
    }
}
