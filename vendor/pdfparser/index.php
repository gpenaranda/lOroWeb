<?php

header('Content-Type: text/html; charset=UTF-8');

include 'vendor/autoload.php';

$message = '';
$texts   = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	try {
        $content = '';

		if (isset($_POST['inputUrl']) && preg_match('/^https?:\/\//', trim($_POST['inputUrl']))) {
			$content = file_get_contents(trim($_POST['inputUrl']));
		} elseif (isset($_FILES['inputFile']) && $_FILES['inputFile']['type'] == 'application/pdf') {
			$content = file_get_contents($_FILES['inputFile']['tmp_name']);
                        $rowFileName  = explode("-",$_FILES['inputFile']['name']);
                        
                        $nombreExp = explode(".",$rowFileName[1]);
		}
		
		if ($content) {
			$parser = new \Smalot\PdfParser\Parser();
			$pdf    = $parser->parseContent($content);
			$pages  = $pdf->getPages();
			
			foreach ($pages as $page) {
				$texts[] = $page->getText();
			}
		} else {
			throw new Exception('Unable to retrieve content. Check if it is really a pdf file.');
		}
	} catch(Exception $e) {
		$message = $e->getMessage();
	}
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Demo page - PDFParser</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
	<div class="container">
		<h1><a href="/">PDF Parser - Demo sample page</a></h1>
		
		<?php if ($message): ?>
			<div class="alert alert-danger"><?php echo $message; ?></div>
		<?php endif; ?>
		
		<form class="form-horizontal" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label for="inputUrl" class="col-lg-2 control-label">Url</label>
				<div class="col-lg-10">
					<input type="url" class="form-control" id="inputUrl" name="inputUrl" placeholder="Url">
				</div>
			</div>
			<div class="form-group">
				<label for="inputFile" class="col-lg-2 control-label">File</label>
				<div class="col-lg-10">
					<input type="file" class="form-control" id="inputFile" name="inputFile" placeholder="File">
				</div>
			</div>
		    <div class="form-group">
				<div class="col-lg-offset-2 col-lg-10">
					<button type="submit" class="btn btn-default">Submit</button>
				</div>
			</div>
		</form>

		<?php if ($texts) : ?>
			<div class="panel-group" id="accordion">
			<?php foreach ($texts as $pos => $text) : 
                            echo 'Proveedor: ';
                            echo $nombreExp[0].'<br>';
                            
                            preg_match('!N° DE RECIBO:\s*(\d+)!i',$text, $a);
                            echo 'Nro de Referencia: ';
                            echo $a[1].'<br>';

                            preg_match("/Fecha:(.*)/", $text, $c);
                            echo 'Fecha: ';
                            echo $c[1].'<br>';                           
                            
                            preg_match('!Monto:\s*(\d.+)!i',$text, $b);
                            echo 'Monto de la Transaccion: ';
                            echo $b[1].'<br>';
                            
                            preg_match("/Concepto:(.*)/", $text, $c);
                            echo 'Concepto: ';
                            echo $c[1].'<br>';
                            
                            ?>
			  <div class="panel panel-default">
				<div class="panel-heading">
				  <h4 class="panel-title">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $pos; ?>">
					  Page #<?php echo ($pos + 1); ?>
					</a>
				  </h4>
				</div>
				<div id="collapse<?php echo $pos; ?>" class="panel-collapse collapse in">
				  <div class="panel-body"><pre>
					<?php echo $text; ?>
				  </pre></div>
				</div>
			  </div>
			<?php endforeach; ?>
			</div>
		<?php endif; ?>
		
		
		<p class="text-center"><a href="http://www.pdfparser.org" target="_blank">PDF Parser</a> is a <a href="http://www.php.net" target="_blank">PHP</a> provided by <a href="https://twitter.com/sebastienmalot" target="_blank">@sebastienmalot</a>.<br/>
		Code licenced under <a href="http://www.pdfparser.org/license" target="_blank">GPLv2 license</a>.
		</p>
		
		<ul class="list-inline text-center">
			<li><a href="http://www.pdfparser.org/documentation" target="_blank">Documentation</a></li>
			<li><a href="https://github.com/smalot/pdfparser" target="_blank">GitHub</a></li>
			<li><a href="https://packagist.org/packages/smalot/pdfparser" target="_blank">Packagist</a></li>
			<li><a href="http://www.pdfparser.org/contact" target="_blank">Contact</a></li>
		</ul>
		
	</div>
	
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>