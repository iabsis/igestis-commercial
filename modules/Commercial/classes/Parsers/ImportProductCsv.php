<?php
namespace Igestis\Modules\Commercial\Parsers;

/**
* Igestis\Modules\Commercial\Parsers\ImportProductCsv
*/
class ImportProductCsv
{
    private $csvFilePointer;
    private $enclosure;
    private $delimiter;
    private $columnNumbers;
    private $lineNumber = 0;

    public function __construct()
    {
        $this->columnNumbers = array();
    }

    public function setColumns($columnNumbers)
    {
        $this->columnNumbers = $columnNumbers;
    }

    public function getCurrentLine() {
        return $this->lineNumber;
    }

    public function loadCsv($csvFile, $delimiter, $enclosure)
    {
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->csvFilePointer = fopen($csvFile, "r");
        if ($this->csvFilePointer === false) {
            throw new \Exception("Unable to open the csv file");
        }
    }

    public function next()
    {
        $this->lineNumber++;
        $csvData = fgetcsv($this->csvFilePointer, 10000, $this->delimiter, $this->enclosure);
        if ($csvData === false) {
            fclose($this->csvFilePointer);
            return false;
        }

        $formattedArray = array();

        foreach ($this->columnNumbers as $columnName => $columnNumber) {
            if ($columnNumber === null || $columnNumber === "") {
                continue;
            }

            $columnNumber--;

            if (!isset($csvData[$columnNumber])) {
                throw new \Exception(sprintf("CSV error (line:%s) : The column number '%s' for field '%s' does not exist", $this->lineNumber, $columnNumber, $columnName));
            }

            $formattedArray[$columnName] = $csvData[$columnNumber];
            
        }

        return $formattedArray;
    }

}