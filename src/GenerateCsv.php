<?php

namespace Vijaykumar\SqlToCsv;

use \PDO;
use Exception;


class GenerateCsv {
    public function generate_csv($conn, $query, $filename='Report') {

        // Create a file pointer
        $filePointer = fopen("php://memory", "w");
        $delimiter = ",";
        $filename .= ".csv";

        $isValid = isSelectQuery($query);

        if(!$isValid) {
            fputcsv($filePointer, ['Only Select query is allowed. data not able to fetch.'], $delimiter);

            fseek($filePointer, 0);

            downloadScript($filename);

            fpassthru($filePointer);
            return;
        }

        try {
            $stmt = $conn->query($query);
            
            if ($stmt->rowCount() > 0) {
    
                $rowData = array();
    
                // Rows data
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($rowData, $row);
                }
                
                $header = $this->get_header($rowData[0]);
    
                fputcsv($filePointer, $header, $delimiter);
                
                // Creating csv
                for ($index = 0; $index < count($rowData); $index++) {
                    $rowForCSV = $this->get_values($rowData[$index]);
                    fputcsv($filePointer, $rowForCSV, $delimiter);
                }
                
                fseek($filePointer, 0);
    
                downloadScript($filename);
    
                fpassthru($filePointer);
            }   
        }
        catch(Exception $e) {
            fputcsv($filePointer, [$e->getMessage()], $delimiter);

            fseek($filePointer, 0);

            downloadScript($filename);

            fpassthru($filePointer);
        }
        
    }

    public function get_values($arr) {
        $temp = array();
        foreach ($arr as $key => $value) {
            array_push($temp, $value);
        }
        return $temp;
    }

    public function modify_header($str) {
        return ucwords(str_replace("_", " ", $str));
    }
    
    public function get_header($data) {
        $header = array();

        foreach ($data as $key => $value) {
            $headerName = $this->modify_header($key);
            array_push($header,$headerName);
        }

        return $header;
    } 
}

function downloadScript($filename) {
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    header('Content-type: application/csv');
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
}

function isSelectQuery($query) {
    // Convert the query to lowercase for case-insensitive comparison
    $lowercaseQuery = strtolower($query);

    // Check if the query contains the "SELECT" keyword
    if (strpos($lowercaseQuery, "select") !== false) {
        return true; // It is a select query
    }

    return false; // It is not a select query
}

?>