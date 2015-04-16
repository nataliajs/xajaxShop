<?php
require_once('xajax_core/xajax.inc.php');
require_once('include/CestaCompra.php');

session_start();

$xajax = new xajax();

$xajax->register(XAJAX_FUNCTION,'add');
$xajax->register(XAJAX_FUNCTION, 'show');
$xajax->register(XAJAX_FUNCTION, 'vaciar');

$xajax->processRequest();

function add($id){
    $cesta=CestaCompra::carga_cesta();
    $cesta->nuevo_articulo($id);
    $cesta->guarda_cesta();
    
    $respuesta=new xajaxResponse();
    return $respuesta;
}

function show(){
    $cesta=CestaCompra::carga_cesta();
    $products=$cesta->get_productos();
    $response='';
    $muestra=new xajaxResponse();
    if(!$cesta->vacia()){   
        error_log('La cesta no esta vacia');
        $muestra->assign('vaciarSubmit' , 'disabled' , false);
        $muestra->assign('comprarSubmit', 'disabled', false);
        foreach($products as $p){
            $response.="<p>".$p->getcodigo()."</p>";
        }
    }else{
        error_log('La cesta SI esta vacia');
        $muestra->assign('vaciarSubmit' , 'disabled' , true);
        $muestra->assign('comprarSubmit' , 'disabled' , true);
        $response='Cesta vacia';
    }
    
    $muestra->assign('show','innerHTML',$response);
    return $muestra;
}

function vaciar(){
    unset($_SESSION['cesta']);
    $cesta=new CestaCompra();
    
    $respuesta = new xajaxResponse();
    return $respuesta;
}
