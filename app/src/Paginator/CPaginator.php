<?php

namespace CR\Paginator;

/**
* A class for creating pagination links.
*/
class CPaginator implements \Anax\DI\IInjectionAware {

	use \Anax\DI\TInjectable;

	/**
    * Create the pagination numbers
    *
    * @param int $count total number of objects
    * @param string @urlpart part of url for creating page link
	* @param array $get array with hits and page from $_GET
	* @param array $customhits array with hits per page
    *
    * @return string $pagelinks, HTML string with pagelinks
    */
    public function paginateGet($count, $urlpart, $get, $customhits = array(5, 10, 15)) {

		$pages = (int)($count / $get['hits']);
		if ($count % $get['hits']) {
			$pages++;
		}

		$pagearray = array();
		for ($i = 0; $i < $pages; $i++) {
			$pagearray[$i] = ['urlpart' => $urlpart, 'hits' => $get['hits'], 'page' => $i * $get['hits'], 'pageno' => $i + 1];
		}

        $pagelinks = $this->getGetHTML($get, $pagearray, $customhits);

        return $pagelinks;
    }

    /**
    * Get html links
    *
	* @param array $get array with hits and page from $_GET
    * @param array @pagearray array with pagination info
	* @param array $customhits array with hits per page
    *
    * @return string $html, html with page links
    */
    private function getGetHTML($get, $pagearray, $customhits) {

		$counter = 0;
		$params = '?';
		foreach ($get as $key => $value) {
			if ($key !== 'hits' && $key !== 'page') {
				$params .= $key.'='.$value;
				$params .= '&';
			}
			$counter++;
		}

        $html = '<div class="paginate-section right-align">';
        $html .= '<div class="hits-per-page">';
		if ($pagearray) {

        foreach ($customhits as $value) {
            $class = $get['hits'] == $value ? 'current-page' : 'page-button';
			$getparams = $params.'hits='.$value.'&page='.$pagearray[0]['page'];
            $html .= "<a class='".$class."' href='".$this->di->get('url')->create(''.$pagearray[0]["urlpart"].'').$getparams."'>".$value."</a>&nbsp;";
        }
	}
        $html .= '<span class="smaller-text">per sida</span></div>';

        foreach ($pagearray as $pagelink) {
            $class = $get['page'] == $pagelink["page"] ? 'current-page' : 'page-button';
			$getparams = $params.'hits='.$pagelink['hits'].'&page='.$pagelink['page'];
            $html .= "&nbsp;<a class='".$class."' href='".$this->di->get('url')->create(''.$pagelink["urlpart"].'').$getparams."'>".$pagelink["pageno"]."</a>";
        }
        $html .= '</div>';

        return $html;
    }

}
