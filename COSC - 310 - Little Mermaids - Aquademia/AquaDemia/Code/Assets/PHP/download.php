<?php
// Define the base directory for uploads
$baseDirectory = "../Uploads/Student/";

// Get the filename from the GET request
$filename = isset($_GET['filename']) ? $_GET['filename'] : null;

// Security measure: Prevent directory traversal attacks
$filename = basename($filename);

if ($filename) {
    $filePath = $baseDirectory . $filename;

    // Ensure the file exists
    if (file_exists($filePath)) {
        // Determine MIME type dynamically
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        // Set headers to display the file inline
        header("Content-Type: $mimeType");
        header('Content-Disposition: inline; filename="' . basename($filename) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');

        // Output the file content
        readfile($filePath);
    } else {
        echo "File does not exist.";
    }
} else {
    echo "No filename specified.";
}
?>
