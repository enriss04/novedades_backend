<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class SaveFile
{
    public static function byRequest($file, $folder, $name = null)
    {
        try {       

            if(is_null($file)){
                return null;
            }

            if(is_null($name)){
                $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            }

            // Generar un nuevo nombre para el archivo
            $newFileName = $name . '.' . $file->getClientOriginalExtension();
    
            // Guardar el archivo con el nuevo nombre en storage/app/public/$folder
            $url = $file->storeAs($folder, $newFileName, 'public');
        
            return $url;

        } catch (\Throwable $th) {

            return null;    

        }
    }

    public static function byPDF($pdf, $folder, $file = 'file.html')
    {
        try {
            
            if(is_null($pdf)){
                return null;
            }

            $filename = $folder . "/" . $file;
        
            Storage::disk('public')->put($filename, $pdf->output());
        
            $url = Storage::url($filename);
            
            return $url;

        } catch (\Throwable $th) {
            
            return null;

        }        
    }
}