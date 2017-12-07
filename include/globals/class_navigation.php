<?php
class pager
{

	var $limit = 50;
	var $offset = 0;
	var $page = 1;
	var $total = 0;
	var $limit_page_num=2000; // Unlimit if $limit_page_num = "~";
	
	function __construct($limit, $total, $page){
		$this->limit = $limit;
		$this->total = $total;

        if ($page > 0) {
		    $this->page = $page;
        } else {
            $this->page = 1;
        }

		$this->offset = ($this->page - 1) * $this->limit;
	}
    
	function page_link()
	{
        $total  = (int) $this->total;
        $limit    = max((int) $this->limit, 1);
        $page     = (int) $this->page;
		
		$page= ($page== "") ? 1 : $page;	
				
		if ( $total > 0 )
		{		
			$total = ceil($total/$limit);
		}

		//Limit page number
		$total = ($this->limit_page_num == "~") ? $total : ((int)($total > $this->limit_page_num) ? $this->limit_page_num : $total);
		
		$v_f = 3;
		$v_a = 2;
		$v_l = 3;
		$max_pages = $v_f + $v_a + $v_l + 5;
		$z_1 = $z_2 = $z_3 = false;
		$pg = $this->page ? $this->page : 1;		
		$work['B_LINK'] = "";
		$work['F_LINK'] = "";
		$work['P_LINK'] = "";
		
		$pgt = $pg-1;
		$prevActive = ($pg == 1)?false:true;
		$work['F_LINK']	=	$this->pagination_start_dots(1, $prevActive);
		$work['P_LINK']	=	$this->pagination_previous_link($pgt, $prevActive);
		
		$work['B_LINK'] .= '<li>';
		for($m = 1; $m <= $total; $m++) {
			if ($total > $max_pages) {
				if (($m > $v_f) && (($m < $pg - $v_a) || ($m > $pg + $v_a)) && ($m < $total - $v_l + 1)) {
					if (!$z_1 && ($m > $v_f)) {
						$work['B_LINK'] .= '<li><a href="#">...</a></li>';
						$z_1 = true;
					}
					else if (!$z_2 && ($m > $pg + $v_a)) {
						//$work['B_LINK'] .= "<span style=\"float:left\">...</span>";
						$z_2 = true;
					}
					continue;
				}
			}
			
			if($m == $pg) $work['B_LINK'] .= $this->pagination_current_page($m);
			else $work['B_LINK'] .= $this->pagination_page_link($m);		
		}	
		$work['B_LINK'] .= '</li>';
		
		$pgs = $pg + 1;
		
		$nextActive = ($pg == $total)?false:true;
		$work['N_LINK']	=	$this->pagination_next_link($pgs, $nextActive);			
		$work['L_LINK']	=	$this->pagination_end_dots($total, $nextActive);
		
		$html = $work['F_LINK'].$work['P_LINK'].$work['B_LINK'].$work['N_LINK'].$work['L_LINK'];
		$option = '<div class="limit">Trang '.$pg.' of '.$total.' ('.$this->total.' items)</div>';
		$html = $this->pagination_make_jump() . '<ul class="pagination">'.$html.'<input type="hidden" value="'.$pg.'" name="p"></ul>' . $option;
		return $html;
	}

	//===========================================================================
	// pagination_make_jump
	//===========================================================================
	function pagination_make_jump() {
		$content = '<div class="limit">Hiển thị #
						<select onchange="submitform();" size="1" class="inputbox" id="limit" name="limit">
							<option '.($this->limit==10?'selected="selected"':'').' value="10">10</option>
							<option '.($this->limit==20?'selected="selected"':'').' value="20">20</option>
							<option '.($this->limit==30?'selected="selected"':'').' value="30">30</option>
							<option '.($this->limit==50?'selected="selected"':'').' value="50">50</option>
							<option '.($this->limit==100?'selected="selected"':'').' value="100">100</option>
							<option '.($this->limit==150?'selected="selected"':'').' value="150">150</option>
							<option '.($this->limit==200?'selected="selected"':'').' value="200">200</option>
							<option '.($this->limit==250?'selected="selected"':'').' value="250">250</option>
							<option '.($this->limit==300?'selected="selected"':'').' value="300">300</option>
							<option '.($this->limit==350?'selected="selected"':'').' value="350">350</option>
							<option '.($this->limit==400?'selected="selected"':'').' value="400">400</option>
							<option '.($this->limit==450?'selected="selected"':'').' value="450">450</option>
							<option '.($this->limit==500?'selected="selected"':'').' value="500">500</option>
						</select>
					</div>';
		return $content;
	}
	
	//===========================================================================
	// pagination_current_page
	//===========================================================================
	function pagination_current_page($page="") {
		$content = '<li class="active"><a href=#">'.$page.'</a></li>';
		return $content;
	}
	
	//===========================================================================
	// pagination_end_dots
	//===========================================================================
	function pagination_end_dots($page="", $active) {
		if ($active) $content = '<li><a onclick="javascript: document.adminForm.p.value='.$page.'; submitform();return false;" title="End" href="javascript:void(0);">Trang cuối</a></li>';
		else $content = '<li class="disabled"><a href=#">Trang cuối</a></li>';
		return $content;
	}
	
	//===========================================================================
	// pagination_next_link
	//===========================================================================
	function pagination_next_link($page="", $active) {
		if ($active) $content = '<li><a onclick="javascript: document.adminForm.p.value='.$page.'; submitform();return false;" title="Next" href="javascript:void(0);">Sau</a></li>';
		else $content = '<li class="disabled"><a href=#">Sau</a></li>';
		return $content;
	}
	
	//===========================================================================
	// pagination_page_link
	//===========================================================================
	function pagination_page_link($page="") {
		$content = '<li><a onclick="javascript: document.adminForm.p.value='.$page.'; submitform();return false;" title="Trang '.$page.'" href="javascript:void(0);">'.$page.'</a></li>';
		return $content;
	}
	
	//===========================================================================
	// pagination_previous_link
	//===========================================================================
	function pagination_previous_link($page="", $active) {
		if ($active) $content = '<li><a onclick="javascript: document.adminForm.p.value='.$page.'; submitform();return false;" title="Prev" href="javascript:void(0);">Trước</a></li>';
		else $content = '<li class="disabled"><a href=#">Trước</a></li>';
		return $content;
	}
	
	//===========================================================================
	// pagination_start_dots
	//===========================================================================
	function pagination_start_dots($page="", $active) {
		if ($active) $content = '<li><a onclick="javascript: document.adminForm.p.value='.$page.'; submitform();return false;" title="Start" href="javascript:void(0);">Trang đầu</a></li>';
		else $content = '<li class="disabled"><a href=#">Trang đầu</a></li>';
		return $content;
	}

}
?>