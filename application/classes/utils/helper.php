<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Funções uteis e genéricas estáticas
 *
 * @package    Kohana
 * @category   Base
 * @author     Renato Ibiapina
 */
class Utils_Helper
{  
    public static function mensagens($acao,$msg='')
    {
        $session = Session::instance();
        switch($acao)
        {
            case 'add':
                if($msg!='')
                {
                    $oldMsg = array();
                    if($session->get('mensagens')!='')
                    {
                        $oldMsg = json_decode($session->get('mensagens'));
                    }
                    $oldMsg[] = $msg;
                    $session->set('mensagens',json_encode($oldMsg));
                }
                break;
            case 'print':
                $msg = $session->get('mensagens');
                $session->delete('mensagens');
                return $msg;
                break;
        }        
    }
    
    public static function data($dt,$format='d/m/Y')
    {
        if($dt != ""){
            $data = new DateTime($dt);
            return date_format($data,$format);
        }
    }

    public static function getUserImage($user = null)
    {
        if($user != null){
            if(file_exists($user->foto)){    
                return "<img class='round_imgList team_".$user->team->id." ' src='".URL::base().$user->foto."' height='20' alt='".ucfirst($user->nome)."' />";            
            }else{
                $nomes = explode(" ", $user->nome);
                $nome = substr($nomes[0], 0, 1);
                if(count($nomes) > 1){
                    $nome.=substr($nomes[1], 0, 1);
                }            
                return "<p class='round_imgList team_".$user->team->id."' ><span>".$nome."</span></p>";
                //return "<img class='round_imgList ' src='".URL::base().'public/image/admin/default.png'."' height='20' alt='".ucfirst($user->nome)."' />";
            }
        }else{
            return "<img class='round_imgList ' src='".URL::base().'public/image/admin/default.png'."' height='20' />";
        }
    }

    public static function setFilters($postList, $parameters, $model){
        $result = array();
        $result['filtros'] = array();
        foreach ($postList as $key => $item) {
            if($item != ''){
                $result['filtros']['filter_'.$key] = json_encode($item);
            }           
        }
        $result['parameters'] = $parameters;
        $result['model'] = $model;

        return $result;
    }

    public static function dataGdocs($dt,$format='d/m/Y')
    {
        if($dt != ""){
            $pos = strpos($dt, "/");
            $count = count(explode("/", $dt));
            if($pos !== false && $count > 1){
                $data = new DateTime($dt);
                return date_format($data,$format);
            }else{
                return $dt." *";
            }
        }else{
            return "-";
        }
    }

    public static function money_format($number, $format = '%n'){
        $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'. 
                  '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/'; 
        if (setlocale(LC_MONETARY, 0) == 'C') { 
            setlocale(LC_MONETARY, ''); 
        } 
        $locale = localeconv(); 
        preg_match_all($regex, $format, $matches, PREG_SET_ORDER); 
        foreach ($matches as $fmatch) { 
            $value = floatval($number); 
            $flags = array( 
                'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ? 
                               $match[1] : ' ', 
                'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0, 
                'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ? 
                               $match[0] : '+', 
                'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0, 
                'isleft'    => preg_match('/\-/', $fmatch[1]) > 0 
            ); 
            $width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0; 
            $left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0; 
            $right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits']; 
            $conversion = $fmatch[5]; 

            $positive = true; 
            if ($value < 0) { 
                $positive = false; 
                $value  *= -1; 
            } 
            $letter = $positive ? 'p' : 'n'; 

            $prefix = $suffix = $cprefix = $csuffix = $signal = ''; 

            $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign']; 
            switch (true) { 
                case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+': 
                    $prefix = $signal; 
                    break; 
                case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+': 
                    $suffix = $signal; 
                    break; 
                case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+': 
                    $cprefix = $signal; 
                    break; 
                case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+': 
                    $csuffix = $signal; 
                    break; 
                case $flags['usesignal'] == '(': 
                case $locale["{$letter}_sign_posn"] == 0: 
                    $prefix = '('; 
                    $suffix = ')'; 
                    break; 
            } 
            if (!$flags['nosimbol']) { 
                $currency = $cprefix . 
                            ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) . 
                            $csuffix; 
            } else { 
                $currency = ''; 
            } 
            $space  = $locale["{$letter}_sep_by_space"] ? ' ' : ''; 

            $value = number_format($value, $right, $locale['mon_decimal_point'], 
                     $flags['nogroup'] ? '' : $locale['mon_thousands_sep']); 
            $value = @explode($locale['mon_decimal_point'], $value); 

            $n = strlen($prefix) + strlen($currency) + strlen($value[0]); 
            if ($left > 0 && $left > $n) { 
                $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0]; 
            } 
            $value = implode($locale['mon_decimal_point'], $value); 
            if ($locale["{$letter}_cs_precedes"]) { 
                $value = $prefix . $currency . $space . $value . $suffix; 
            } else { 
                $value = $prefix . $value . $space . $currency . $suffix; 
            } 
            if ($width > 0) { 
                $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ? 
                         STR_PAD_RIGHT : STR_PAD_LEFT); 
            } 

            $format = str_replace($fmatch[0], $value, $format); 
        } 
        return $format; 
    }
    
    public static function limparStr($str)
    {
        $str = strtolower($str);
        $a = array('â','ã','à','á','ä','ê','è','é','ë','î','í','ì','ï','ô','õ','ò','ó','ö','û','ú','ù','ü','ç',' ','+');
        $b = array('a','a','a','a','a','e','e','e','e','i','i','i','i','o','o','o','o','o','u','u','u','u','c','_','_');        
        return str_replace($a,$b,$str);
    }
    
    public static function debug($var,$exit=true)
    {
        print '<pre>';
        print_r($var);
        print '</pre>';
        if($exit) exit;
    }
    
    public static function uploadNoAssoc($file,$pasta,$tipagem = array('jpg','jpeg','gif','png'))
    {
        $erro = array(
            1=>'Tipo incorreto de arquivo',
            2=>'Erro ao fazer o upload do arquivo',
        );

        if(Upload::type($file,$tipagem))
        {
            $fName = Utils_Helper::limparStr($file['name']);
            $basedir = 'public/upload/'.$pasta.'/';
            $rootdir = DOCROOT.$basedir;
            $ext = explode(".",$fName);
            $ext = end($ext);                
            $nomeArquivo = str_replace(".$ext","",$fName);

            $fileName = $nomeArquivo.'_'.(time()).'.'.$ext;
            if(Upload::save($file,$fileName,$rootdir,0777))
            {
                return $basedir.$fileName;
            }else
            {
                return 2;
            }
        }else
        {
            return 1;
        }
    }
    
    public static function getExt($filename){
        $ext = explode('.',$filename);
        return end($ext);
    }
    
    public static function getDefaultExtPreview()
    {   
        return array(
            'image'=>array('jpg','jpeg','gif','png'),
            'audio'=>array('mp3','wav'),
            'video'=>array('mp4','avi','ogg'),
            'pdf'=>array('pdf')
        );
    }
    
    public static function preview($file){
        
        $has_preview = false;
        $ext = self::getExt($file->uri);
        foreach(self::getDefaultExtPreview() as $key=>$arr){
            if(in_array($ext,$arr)){
                $has_preview = true;
                break;
            }
        }        
        if($has_preview){
            if($ext=='pdf'){
                return '<a href="'.URL::base().$file->uri.'" target="_blank" title="Preview" class="preview floatNone">Preview</a></li>';
            }else{
                return '<a href="javascript:openPop(\'/admin/files/preview/'.$file->id.'\');" title="Preview" class="preview floatNone">Preview</a></li>';
            }
        }else{
            return '';
        }
    }
    
    public static function getSize($val)
    {
        if($val < 1024){
            return $val.' b';
        }else{
            $kbval = (int)($val / 1024);
            if($kbval < 1024){
                return $kbval.' kb';
            }else{
                $mbval = (int)($kbval / 1024);
                if($mbval < 1024){
                    return $mbval.' mb';
                }else{
                    $gbval = (int)($kbval / 1024);
                    return $gbval.' gb';
                }
            }
        }
    }

    public static function getDay($date){
        $dw = date( "w", strtotime($date));
        switch ($dw) {
            case '1':
                return 'Segunda';
                break;
            case '2':
                return 'Terça';
                break;
            case '3':
                return 'Quarta';
                break;
            case '4':
                return 'Quinta';
                break;
            case '5':
                return 'Sexta';
                break;
            case '6':
                return 'Sábado';
                break;
            case '7':
                return 'Domingo';
                break;
            
            default:
                # code...
                break;
        }
    }
}
?>
