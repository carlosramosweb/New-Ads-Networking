<?php

function getMonthBR($month) {
    switch ($month) {
        case '01':
            return "Janeiro";
            break;
        case '02':
            return "Fevereiro";  
            break;
        case '03':
            return "Março";  
            break;     
        case '04':
            return "Abril";  
            break;            
        case '05':
            return "Maio"; 
            break;          
        case '06':
            return "Junho"; 
            break;         
        case '07':
            return "Julho";  
            break;  
        case '08':
            return "Agosto";   
            break;      
        case '09':
            return "Setembro";  
            break;  
        case '10':
            return "Outubro";
            break;       
        case '11':
            return  "Novembro";  
            break;                  
        case '12':
            return "Dezembro";
            break;
        default:
            return "N/A";
            break;
    }
}

function get_project_url_filter($category, $category_id) {
    if (isset($category) && isset($category_id)) {
        $budget_filter = '';
        if ($category == 'category') {
            $budget_filter .= '&category=' . $category_id;
        }
        $action_url = 'budgets.php?action=filter';
        $budget_url = INCLUDE_PATH_PAINEL . $action_url . $budget_filter;
        return $budget_url;
    } else {
        return false;
    }
}

function getAccepted($accepted) {
    if ($accepted == 1) {
        return "Orçamentos Aceita";
    } else if ($accepted == 0 || $accepted == '') {
        return "Aguardando...";
    }
}

function getStatusPayments($status) {
    if ($status == 1) {
        return '<span class="badge badge-success">Pago</span>';
    } else if ($status == 0 || $status == '') {
        return '<span class="badge badge-danger">Pendente</span>';
    }
}

function getTypePayments($type) {
    if ($type == 1) {
        return "Real";
    } else if ($type == 2 || $type == '') {
        return "%";
    }
}

function getCurrency($currency) {
    switch ($currency) {
        case '1':
            return 'R$';
            break;
        case '2':
            return '$';
            break;
        case '3':
            return '€';
            break;
        default:
            return 'R$';
            break;
    }
}

function getPaidOut($paid_out) {
    if ($paid_out == 1) {
        return '<span class="badge badge-success">Pago</span>';
    } else if ($paid_out == 2 || $paid_out == 0 || $paid_out == '') {
        return '<span class="badge badge-danger">À Pagar</span>';
    }
}

function getStatus($status) {
    if ($status == 1) {
        return "Ativo";
    } else if ($status == 0 || $status == '') {
        return "Desativado";
    }
}

function get_the_pagination($count, $paged) {
    $per_page = ($count/50);
    echo '<div class="col-sm-12 col-md-12" style="margin-bottom: 25px;">';
        echo '<div class="dataTables_paginate paging_simple_numbers">';
            echo '<ul class="pagination">';
    for ($page=1;$page<=$per_page;$page++) {
        $active = '';
        if (isset($_GET['page'])) {
            if ($page == $_GET['page']) {
                $active = 'active';
            }
        } 
        if (!isset($_GET['page']) && $page == 1) {
            $active = 'active';
        }
        echo '<li class="paginate_button page-item ' . $active . '">';
            echo '<a href="' . INCLUDE_PATH_PAINEL . $paged . '.php?page=' . $page . '" class="page-link">' . $page . '</a> ';
        echo '</li>';
    }
            echo '</ul>';
        echo '</div>';
    echo '</div>';
}

function get_sidebar_item($pages) {
    $current_url = trim( $_SERVER['REQUEST_URI'] );
    $current_url = str_replace("/Painel/", "", $current_url);  
    $current_url = str_replace(".php", "", $current_url); 
    $current_url = str_replace("?action=sync", "", $current_url); 
    $current_url = str_replace("?action=edit", "", $current_url);    
    $current_url = str_replace("?action=delete", "", $current_url);
    $current_url = str_replace("?action=register", "", $current_url);
    $current_url = str_replace("?alert=error", "", $current_url);
    $current_url = str_replace("?alert=success", "", $current_url);
    $current_url = str_replace("?action=upload-files", "", $current_url);
    $current_url = str_replace("?action=upload-images", "", $current_url);
    $current_url = str_replace("?success=delete", "", $current_url);
    $current_url = str_replace("?error=delete", "", $current_url);
    $current_url = str_replace("?project_id=", "", $current_url);
    $current_url = str_replace("&budget_id=", "", $current_url);
    $current_url = str_replace("&category_id=", "", $current_url);    
    $current_url = str_replace("&id=", "", $current_url); 
    $current_url = str_replace("?id=", "", $current_url);
    $current_url = str_replace("id=", "", $current_url);
    $current_url = str_replace("?search=true", "", $current_url);  
    $current_url = preg_replace("/[0-9]+/", '', $current_url);
    
    if (in_array($current_url, $pages, true)) {
        return true;
    } else {
        return false;
    }
}

function getFormatDate($date, $format) {
    if (isset($date) && !empty($date)) {
        if (isset($format) && $format == '0') {
            $timestamp = strtotime($date); 
            $newDate = date("d/m/Y", $timestamp );
        }
        if (isset($format) && $format == '1') {
            $timestamp = strtotime($date); 
            $newDate = date("d/m/Y H:i:s", $timestamp );
        }
        if (isset($format) && $format == '2') {
            $timestamp = strtotime($date); 
            $newDate = date("Y-m-d H:i:s", $timestamp );
        }
    }
    return $newDate;
}


function is_email( $email ) { 
    if ( strlen( $email ) < 6 ) {
        return false;
    } 
    if ( strpos( $email, '@', 1 ) === false ) {
        return false;
    }
    list( $local, $domain ) = explode( '@', $email, 2 );
 
    if ( ! preg_match( '/^[a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~\.-]+$/', $local ) ) {
        return false;
    }
 
    if ( preg_match( '/\.{2,}/', $domain ) ) {
        return false;
    } 
    if ( trim( $domain, " \t\n\r\0\x0B." ) !== $domain ) {
        return false;
    }
    $subs = explode( '.', $domain ); 
    if ( 2 > count( $subs ) ) {
        return false;
    } 
    foreach ( $subs as $sub ) {
        if ( trim( $sub, " \t\n\r\0\x0B-" ) !== $sub ) {
            return false;
        }
        if ( ! preg_match( '/^[a-z0-9-]+$/i', $sub ) ) {
            return false;
        }
    }
    return true;
}

function clear_document($number) {
    if (isset($number)) {
        $number = str_replace(" ", "", $number);
        $number = str_replace(".", "", $number);
        $number = str_replace(",", "", $number);
        $number = str_replace("-", "", $number);
        $number = str_replace("/", "", $number);
        $number = str_replace("{", "", $number);
        $number = str_replace("}", "", $number);
    }
    return $number;
}

function format_document($number) {
    if (isset($number) && strlen($number) == 11) {
        $number = format_mask($number, '###.###.###-##');
    } else if (isset($number) && strlen($number) > 11) {
        $number = format_mask($number, '##.###.###/####-##');
    }
    return $number;
}

function format_mask($number, $mask) {
    $maskared = '';
    $k = 0;
    for($i = 0; $i<=strlen($mask)-1; $i++) {
        if($mask[$i] == '#') {
            if(isset($number[$k])) $maskared .= $number[$k++];
        } else {
            if(isset($mask[$i])) $maskared .= $mask[$i];
        }
    }
    return $maskared;
}

function sanitizeCategory( $cat_name, $strict = false ) {
    $cat_name = strip_all_tags( $cat_name );
    $cat_name = remove_accents( $cat_name );
    $cat_name = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $cat_name );
    $cat_name = preg_replace( '/&.+?;/', '', $cat_name ); 
    if ( $strict ) {
        $cat_name = preg_replace( '|[^a-z0-9 _.\-@]|i', '', $cat_name );
    }     
    $cat_name = strtolower($cat_name); 
    $cat_name = str_replace(' ', '-', $cat_name); 
    $cat_name = trim( $cat_name );
    $cat_name = preg_replace( '|\s+|', ' ', $cat_name ); 
    return $cat_name;
}

function sanitizeUser( $username, $strict = false ) {
    $username = strip_all_tags( $username );
    $username = remove_accents( $username );
    $username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
    $username = preg_replace( '/&.+?;/', '', $username ); 
    if ( $strict ) {
        $username = preg_replace( '|[^a-z0-9 _.\-@]|i', '', $username );
    }
    $username = strstr($username, '@', true);        
    $username = trim( $username );
    $username = preg_replace( '|\s+|', ' ', $username ); 
    return $username;
}

function strip_all_tags( $string, $remove_breaks = false ) {
    $string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
    $string = strip_tags( $string ); 
    if ( $remove_breaks ) {
        $string = preg_replace( '/[\r\n\t ]+/', ' ', $string );
    } 
    return trim( $string );
}

function remove_accents( $string, $locale = '' ) {
            if ( ! preg_match( '/[\x80-\xff]/', $string ) ) {
                return $string;
            }
         
            if ( $string ) {
                $chars = array(
                    // Decompositions for Latin-1 Supplement.
                    'ª' => 'a',
                    'º' => 'o',
                    'À' => 'A',
                    'Á' => 'A',
                    'Â' => 'A',
                    'Ã' => 'A',
                    'Ä' => 'A',
                    'Å' => 'A',
                    'Æ' => 'AE',
                    'Ç' => 'C',
                    'È' => 'E',
                    'É' => 'E',
                    'Ê' => 'E',
                    'Ë' => 'E',
                    'Ì' => 'I',
                    'Í' => 'I',
                    'Î' => 'I',
                    'Ï' => 'I',
                    'Ð' => 'D',
                    'Ñ' => 'N',
                    'Ò' => 'O',
                    'Ó' => 'O',
                    'Ô' => 'O',
                    'Õ' => 'O',
                    'Ö' => 'O',
                    'Ù' => 'U',
                    'Ú' => 'U',
                    'Û' => 'U',
                    'Ü' => 'U',
                    'Ý' => 'Y',
                    'Þ' => 'TH',
                    'ß' => 's',
                    'à' => 'a',
                    'á' => 'a',
                    'â' => 'a',
                    'ã' => 'a',
                    'ä' => 'a',
                    'å' => 'a',
                    'æ' => 'ae',
                    'ç' => 'c',
                    'è' => 'e',
                    'é' => 'e',
                    'ê' => 'e',
                    'ë' => 'e',
                    'ì' => 'i',
                    'í' => 'i',
                    'î' => 'i',
                    'ï' => 'i',
                    'ð' => 'd',
                    'ñ' => 'n',
                    'ò' => 'o',
                    'ó' => 'o',
                    'ô' => 'o',
                    'õ' => 'o',
                    'ö' => 'o',
                    'ø' => 'o',
                    'ù' => 'u',
                    'ú' => 'u',
                    'û' => 'u',
                    'ü' => 'u',
                    'ý' => 'y',
                    'þ' => 'th',
                    'ÿ' => 'y',
                    'Ø' => 'O',
                    // Decompositions for Latin Extended-A.
                    'Ā' => 'A',
                    'ā' => 'a',
                    'Ă' => 'A',
                    'ă' => 'a',
                    'Ą' => 'A',
                    'ą' => 'a',
                    'Ć' => 'C',
                    'ć' => 'c',
                    'Ĉ' => 'C',
                    'ĉ' => 'c',
                    'Ċ' => 'C',
                    'ċ' => 'c',
                    'Č' => 'C',
                    'č' => 'c',
                    'Ď' => 'D',
                    'ď' => 'd',
                    'Đ' => 'D',
                    'đ' => 'd',
                    'Ē' => 'E',
                    'ē' => 'e',
                    'Ĕ' => 'E',
                    'ĕ' => 'e',
                    'Ė' => 'E',
                    'ė' => 'e',
                    'Ę' => 'E',
                    'ę' => 'e',
                    'Ě' => 'E',
                    'ě' => 'e',
                    'Ĝ' => 'G',
                    'ĝ' => 'g',
                    'Ğ' => 'G',
                    'ğ' => 'g',
                    'Ġ' => 'G',
                    'ġ' => 'g',
                    'Ģ' => 'G',
                    'ģ' => 'g',
                    'Ĥ' => 'H',
                    'ĥ' => 'h',
                    'Ħ' => 'H',
                    'ħ' => 'h',
                    'Ĩ' => 'I',
                    'ĩ' => 'i',
                    'Ī' => 'I',
                    'ī' => 'i',
                    'Ĭ' => 'I',
                    'ĭ' => 'i',
                    'Į' => 'I',
                    'į' => 'i',
                    'İ' => 'I',
                    'ı' => 'i',
                    'Ĳ' => 'IJ',
                    'ĳ' => 'ij',
                    'Ĵ' => 'J',
                    'ĵ' => 'j',
                    'Ķ' => 'K',
                    'ķ' => 'k',
                    'ĸ' => 'k',
                    'Ĺ' => 'L',
                    'ĺ' => 'l',
                    'Ļ' => 'L',
                    'ļ' => 'l',
                    'Ľ' => 'L',
                    'ľ' => 'l',
                    'Ŀ' => 'L',
                    'ŀ' => 'l',
                    'Ł' => 'L',
                    'ł' => 'l',
                    'Ń' => 'N',
                    'ń' => 'n',
                    'Ņ' => 'N',
                    'ņ' => 'n',
                    'Ň' => 'N',
                    'ň' => 'n',
                    'ŉ' => 'n',
                    'Ŋ' => 'N',
                    'ŋ' => 'n',
                    'Ō' => 'O',
                    'ō' => 'o',
                    'Ŏ' => 'O',
                    'ŏ' => 'o',
                    'Ő' => 'O',
                    'ő' => 'o',
                    'Œ' => 'OE',
                    'œ' => 'oe',
                    'Ŕ' => 'R',
                    'ŕ' => 'r',
                    'Ŗ' => 'R',
                    'ŗ' => 'r',
                    'Ř' => 'R',
                    'ř' => 'r',
                    'Ś' => 'S',
                    'ś' => 's',
                    'Ŝ' => 'S',
                    'ŝ' => 's',
                    'Ş' => 'S',
                    'ş' => 's',
                    'Š' => 'S',
                    'š' => 's',
                    'Ţ' => 'T',
                    'ţ' => 't',
                    'Ť' => 'T',
                    'ť' => 't',
                    'Ŧ' => 'T',
                    'ŧ' => 't',
                    'Ũ' => 'U',
                    'ũ' => 'u',
                    'Ū' => 'U',
                    'ū' => 'u',
                    'Ŭ' => 'U',
                    'ŭ' => 'u',
                    'Ů' => 'U',
                    'ů' => 'u',
                    'Ű' => 'U',
                    'ű' => 'u',
                    'Ų' => 'U',
                    'ų' => 'u',
                    'Ŵ' => 'W',
                    'ŵ' => 'w',
                    'Ŷ' => 'Y',
                    'ŷ' => 'y',
                    'Ÿ' => 'Y',
                    'Ź' => 'Z',
                    'ź' => 'z',
                    'Ż' => 'Z',
                    'ż' => 'z',
                    'Ž' => 'Z',
                    'ž' => 'z',
                    'ſ' => 's',
                    // Decompositions for Latin Extended-B.
                    'Ș' => 'S',
                    'ș' => 's',
                    'Ț' => 'T',
                    'ț' => 't',
                    // Euro sign.
                    '€' => 'E',
                    // GBP (Pound) sign.
                    '£' => '',
                    // Vowels with diacritic (Vietnamese).
                    // Unmarked.
                    'Ơ' => 'O',
                    'ơ' => 'o',
                    'Ư' => 'U',
                    'ư' => 'u',
                    // Grave accent.
                    'Ầ' => 'A',
                    'ầ' => 'a',
                    'Ằ' => 'A',
                    'ằ' => 'a',
                    'Ề' => 'E',
                    'ề' => 'e',
                    'Ồ' => 'O',
                    'ồ' => 'o',
                    'Ờ' => 'O',
                    'ờ' => 'o',
                    'Ừ' => 'U',
                    'ừ' => 'u',
                    'Ỳ' => 'Y',
                    'ỳ' => 'y',
                    // Hook.
                    'Ả' => 'A',
                    'ả' => 'a',
                    'Ẩ' => 'A',
                    'ẩ' => 'a',
                    'Ẳ' => 'A',
                    'ẳ' => 'a',
                    'Ẻ' => 'E',
                    'ẻ' => 'e',
                    'Ể' => 'E',
                    'ể' => 'e',
                    'Ỉ' => 'I',
                    'ỉ' => 'i',
                    'Ỏ' => 'O',
                    'ỏ' => 'o',
                    'Ổ' => 'O',
                    'ổ' => 'o',
                    'Ở' => 'O',
                    'ở' => 'o',
                    'Ủ' => 'U',
                    'ủ' => 'u',
                    'Ử' => 'U',
                    'ử' => 'u',
                    'Ỷ' => 'Y',
                    'ỷ' => 'y',
                    // Tilde.
                    'Ẫ' => 'A',
                    'ẫ' => 'a',
                    'Ẵ' => 'A',
                    'ẵ' => 'a',
                    'Ẽ' => 'E',
                    'ẽ' => 'e',
                    'Ễ' => 'E',
                    'ễ' => 'e',
                    'Ỗ' => 'O',
                    'ỗ' => 'o',
                    'Ỡ' => 'O',
                    'ỡ' => 'o',
                    'Ữ' => 'U',
                    'ữ' => 'u',
                    'Ỹ' => 'Y',
                    'ỹ' => 'y',
                    // Acute accent.
                    'Ấ' => 'A',
                    'ấ' => 'a',
                    'Ắ' => 'A',
                    'ắ' => 'a',
                    'Ế' => 'E',
                    'ế' => 'e',
                    'Ố' => 'O',
                    'ố' => 'o',
                    'Ớ' => 'O',
                    'ớ' => 'o',
                    'Ứ' => 'U',
                    'ứ' => 'u',
                    // Dot below.
                    'Ạ' => 'A',
                    'ạ' => 'a',
                    'Ậ' => 'A',
                    'ậ' => 'a',
                    'Ặ' => 'A',
                    'ặ' => 'a',
                    'Ẹ' => 'E',
                    'ẹ' => 'e',
                    'Ệ' => 'E',
                    'ệ' => 'e',
                    'Ị' => 'I',
                    'ị' => 'i',
                    'Ọ' => 'O',
                    'ọ' => 'o',
                    'Ộ' => 'O',
                    'ộ' => 'o',
                    'Ợ' => 'O',
                    'ợ' => 'o',
                    'Ụ' => 'U',
                    'ụ' => 'u',
                    'Ự' => 'U',
                    'ự' => 'u',
                    'Ỵ' => 'Y',
                    'ỵ' => 'y',
                    // Vowels with diacritic (Chinese, Hanyu Pinyin).
                    'ɑ' => 'a',
                    // Macron.
                    'Ǖ' => 'U',
                    'ǖ' => 'u',
                    // Acute accent.
                    'Ǘ' => 'U',
                    'ǘ' => 'u',
                    // Caron.
                    'Ǎ' => 'A',
                    'ǎ' => 'a',
                    'Ǐ' => 'I',
                    'ǐ' => 'i',
                    'Ǒ' => 'O',
                    'ǒ' => 'o',
                    'Ǔ' => 'U',
                    'ǔ' => 'u',
                    'Ǚ' => 'U',
                    'ǚ' => 'u',
                    // Grave accent.
                    'Ǜ' => 'U',
                    'ǜ' => 'u',
                );
         
                $string = strtr( $string, $chars );
            } else {
                $chars = array();
                // Assume ISO-8859-1 if not UTF-8.
                $chars['in'] = "\x80\x83\x8a\x8e\x9a\x9e"
                    . "\x9f\xa2\xa5\xb5\xc0\xc1\xc2"
                    . "\xc3\xc4\xc5\xc7\xc8\xc9\xca"
                    . "\xcb\xcc\xcd\xce\xcf\xd1\xd2"
                    . "\xd3\xd4\xd5\xd6\xd8\xd9\xda"
                    . "\xdb\xdc\xdd\xe0\xe1\xe2\xe3"
                    . "\xe4\xe5\xe7\xe8\xe9\xea\xeb"
                    . "\xec\xed\xee\xef\xf1\xf2\xf3"
                    . "\xf4\xf5\xf6\xf8\xf9\xfa\xfb"
                    . "\xfc\xfd\xff";
         
                $chars['out'] = 'EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy';
         
                $string              = strtr( $string, $chars['in'], $chars['out'] );
                $double_chars        = array();
                $double_chars['in']  = array( "\x8c", "\x9c", "\xc6", "\xd0", "\xde", "\xdf", "\xe6", "\xf0", "\xfe" );
                $double_chars['out'] = array( 'OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th' );
                $string              = str_replace( $double_chars['in'], $double_chars['out'], $string );
            }
         
            return $string;
        }