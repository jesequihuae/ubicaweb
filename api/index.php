<?php 

    require 'vendor/autoload.php';
    require 'conection.php';
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    $c = new \Slim\Container();
    $c['errorHandler'] = function ($c) {
        return function ($request, $response, $exception) use ($c) {
        	$error = array('error' => $exception->getMessage());
          return $c['response']->withStatus(500)
                                 ->withHeader('Content-Type', 'application/json')
                                 ->write(json_encode($error));
        };
    };

    $app = new \Slim\App($c);

    require 'utils.php';

    $app->get('/',function(Request $request, Response $response, $args){
        echo 'hola mundo';
    });

    $app->get('/getTables', function(Request $request, Response $response, $args) {
        $Edificios = EdificiosLoncherias::where('bEdificioLoncheria','=',1)->where('bActivo','=',1)->get();
        foreach ($Edificios as $Edificio) { 
            if($Edificio->vImgEdificio == '../img/buildings/default.png') {
                $Edificio->imgBase64 = 'default';
            } else {
                $path = $Edificio->vImgEdificio;
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $Edificio->imgBase64 = $base64;
            }
        }

        $Loncherias = EdificiosLoncherias::where('bEdificioLoncheria','=',0)->where('bActivo','=',1)->get();
        foreach ($Loncherias as $Loncheria) { 
            if($Loncheria->vImgEdificio == '../img/foodbuilding/default.png') {
                $Loncheria->imgBase64 = 'default';
            } else {
                $path = $Loncheria->vImgEdificio;
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $Loncheria->imgBase64 = $base64;
            }
        }

        $Departamentos = Departamentos::where('bActivo','=',1)->get();
        foreach ($Departamentos as $Departamento) { 
            if($Departamento->vImgResponsable == '../img/departments/default.png') {
                $Departamento->imgBase64 = 'default';
            } else {
                $path = $Departamento->vImgResponsable;
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $Departamento->imgBase64 = $base64;
            }
        }

        $Horarios = Horarios::get();

        // $Tablas = array();
        // array_push($Tablas, array("Edificios"     => $Edificios));
        // array_push($Tablas, array("Loncherias"    => $Loncherias));
        // array_push($Tablas, array("Departamentos" => $Departamentos));
        // array_push($Tablas, array("Horarios"      => $Horarios));
  
        // return sendOkResponse(json_encode($Tablas), $response);
        return sendOkResponse(json_encode(
            array(
                "Edificios"     => $Edificios,
                "Loncherias"    => $Loncherias,
                "Departamentos" => $Departamentos,
                "Horarios"      => $Horarios
                )
            ), $response);
    });
    
    $app->run();

?>