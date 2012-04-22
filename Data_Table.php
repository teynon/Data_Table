<?php

####################################################
# Data Table Class                                 #
# ------------------------------------------------ #
#    This class creates a data table that can be   #
# easily manipulated and sorted. I have intentions #
# of extending this class later to allow exporting #
# of any table to an excel (xls) document.         #
#                                                  #
#    This class makes use of my DOMe class.        #
#                                                  #
# ------------------------------------------------ #
# CREATED BY: Thomas Eynon                         #
#             www.thomaseynon.com                  #
# CREATED ON: 1/18/2012                            #
####################################################

require "../DOMe/DOMe.php";

class Data_Table {
	public $table, $thead, $tbody, $tr, $th, $td;
	public $rows, $row, $columns, $column, $cells, $autoFill, $rowLimit, $id, $headerRepeat;
	
	function __construct($id = NULL, $autoFill = true) {
		
		$this->table = new DOMe("table");
		
		// Initialize to an empty table.
		$this->thead = NULL;
		$this->tbody = NULL;
		$this->tr = array();
		$this->th = array();
		$this->td = array();
		
		// Track the total rows, columns, and cells in the table.
		$this->row = 0;
		$this->rows = 0;
		$this->columns = 0;
		$this->cells = 0;
		
		// By default, header never repeats.
		$this->headerRepeat = 0;
		
		if ($id == NULL) {
			// Create a random name.
			$id = "datatable_";
			
			$string = "abcdefghijklmnopqrstuvwxyz0123456789";
			for ($i = 0; $i < 15; $i++) {
				$id .= $string[rand(0, strlen($string) -1)];
			}
		}
		
		$this->id = $id;
		
		// When adding rows, should we create cells even if the columns weren't specified? Default is false.
		//  - The table column count will be defined by the first row added unless set by the user.
		//    So if the column count is 4 and we add a row that only specifies 3 columns, should we still
		//    build the table cell? (td)
		$this->autoFill = $autoFill;
	}
	
	// Headers should always come before the tbody.
	function addHeader($data) {
		// Create the thead
		$this->thead = new DOMe("thead");
		
		if ($this->tbody != NULL) {
			$this->table->insertBefore($this->tbody, $this->thead);
		}
		else {
			$this->table->assign($this->thead);
		}
		
		// Define a header row.
		$this->tr[$this->row] = &$this->thead->newChild("tr");
		
		// Assign the data to a cell.
		foreach ($data as $key => $value) {
			$this->th[$this->row][$key] = &$this->tr[$this->row]->newChild("th", $value);
		}
		
		$this->columns = count($data);
		++$this->rows;
		$this->cells += $this->columns;
		
		return $this->row++;
	}
	
	function addRow($data) {
		// Create the tbody if it doesn't already exist.
		if ($this->tbody == NULL) {
			$this->tbody = &$this->table->newChild("tbody");
		}
		
		// Define a data row.
		$this->tr[$this->row] = &$this->tbody->newChild("tr");
		
		// Assign the data to a cell.
		foreach ($data as $key => $value) {
			$this->td[$this->row][$key] = &$this->tr[$this->row]->newChild("td", $value);
		}
		
		if (count($data) < $this->columns && $this->autoFill === true) {
			// Create the rest of the cells.
			for ($i = count($data); $i < $this->columns; $i++) {
				$this->td[$this->row][$key] = $this->tr[$this->row]->newChild("td");
			}
		}
		
		$this->rows++;
		$this->cells += $this->columns;
		
		return $this->row++;
	}
	
	function generate() {
		// Copy the DOM Object so we can modify it for output.
		$output = $this->table;
		
		// See if the header repeats and a header exists.
		if ($this->headerRepeat != 0 && is_int($this->headerRepeat) && (count($this->th) > 0)) {
			// Get the row for the header.
			$keys = array_keys($this->th);
			
			// Find first tbody element
			$tbody = $output->getElementByTagName("tbody");
			
			// Loop through the DOM and insert headers every N rows.
			for ($x = $this->headerRepeat; $x < count($tbody); $x = $x + $this->headerRepeat) {
				$headerNode[$x] = $this->tr[$keys[0]]->copy();
				$node = &$tbody->child($x);
				$tbody->insertBefore($node, $headerNode[$x]);
				$x++;
			}
		}
		
		return $output->generate(false);
	}
	
	function sort($headerKey) {
		// We can only sort if there is only one header and the specified key exists.
		if (count($this->th) == 1) {
			// Get the row number.
			foreach ($this->th as $row => $cell) {
				if (isset($this->th[$row][$headerKey])) {
					// Now we can sort the table.
					$sortValues = array();
					
					// Pull the data from the specified column into an array.
					foreach ($this->td as $row => $value) {
						$sortValues[$row] = $this->td[$row][$headerKey]->text;
					}
					
					// Sort the data.
					asort($sortValues);
					
					// Build a new tbody
					$tbody = new DOMe("tbody");
					
					// Loop through the array and rebuild the tbody.
					foreach ($sortValues as $row => $value) {
						// Assign the row to the tbody
						$tbody->assign($this->tr[$row]);
					}
					
					// Remove the current tbody.
					//$this->table->remove($this->tbody);
					
					// Reset the tbody to the new sorted tbody.
					$this->tbody = $tbody;
					
					// Add the tbody to the table.
					//$this->table->assign($this->tbody);
				}
			}
		}
	}
	
	function rsort($headerKey) {
		// We can only sort if there is only one header and the specified key exists.
		if (count($this->th) == 1) {
			// Get the row number.
			foreach ($this->th as $row => $cell) {
				if (isset($this->th[$row][$headerKey])) {
					// Now we can sort the table.
					$sortValues = array();
					
					// Pull the data from the specified column into an array.
					foreach ($this->td as $row => $value) {
						$sortValues[$row] = $this->td[$row][$headerKey]->text;
					}
					
					// Sort the data.
					arsort($sortValues);
					
					// Build a new tbody
					$tbody = new DOMe("tbody");
					
					// Loop through the array and rebuild the tbody.
					foreach ($sortValues as $row => $value) {
						// Assign the row to the tbody
						$tbody->assign($this->tr[$row]);
					}
					
					// Remove the current tbody.
					//$this->table->remove($this->tbody);
					
					// Reset the tbody to the new sorted tbody.
					$this->tbody = $tbody;
					
					// Add the tbody to the table.
					//$this->table->assign($this->tbody);
				}
			}
		}
	}
        
	// Imports a valid DOM Table Node
	function importDOM($table) {
		            
	}
}

