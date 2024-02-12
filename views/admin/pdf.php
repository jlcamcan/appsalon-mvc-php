
<?php 
//debuguear($fechaFormateada);
setlocale(LC_MONETARY, 'es_ES');
require('../vendor/fpdf/fpdf.php');
$EURO = " " . chr(128);

class PDF extends FPDF
{
   // Cabecera de página
   function Header()
   {
      $this->Image('../public/build/img/logo-2.jpg', 10, 10, 40); //logo de la empresa,moverDerecha,moverAbajo,tamañoIMG
      $this->SetFont('Arial', 'B', 19); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(45); // Movernos a la derecha
      $this->SetTextColor(0, 0, 0); //color
      //creamos una celda o fila
      $this->Cell(110, 15, utf8_decode('PELUQUERÍA JOSÉ LUIS'), 0, 1, 'C', 0); // AnchoCelda,AltoCelda,titulo,borde(1-0),saltoLinea(1-0),posicion(L-C-R),ColorFondo(1-0)
      $this->Ln(3); // Salto de línea
      $this->SetTextColor(103); //color
   
      /* TITULO DE LA TABLA */
      //color
      $this->SetTextColor(228, 100, 0);
      $this->Cell(50); // mover a la derecha
      $this->SetFont('Arial', 'B', 15);
      $this->Cell(100, 10, utf8_decode("CITAS DEL DÍA: "). $this->formatearFecha($_POST['fecha']), 0, 1, 'C', 0);
      $this->Ln(7);

      /* CAMPOS DE LA TABLA */
      //color
      $this->SetFillColor(228, 100, 0); //colorFondo
      $this->SetTextColor(255, 255, 255); //colorTexto
      $this->SetDrawColor(163, 163, 163); //colorBorde
      $this->SetFont('Arial', 'B', 11);
      $this->Cell(20, 10, utf8_decode('HORA'), 1, 0, 'C', 1);
      $this->Cell(70, 10, utf8_decode('CLIENTE'), 1, 0, 'C', 1);
      $this->Cell(55, 10, utf8_decode('EMAIL'), 1, 0, 'C', 1);
      $this->Cell(45, 10, utf8_decode('TELÉFONO'), 1, 0, 'C', 1);
      $this->Ln(10);
   }

   // Pie de página
   function Footer()
   {
      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); //pie de pagina(numero de pagina)

      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, cursiva, tamañoTexto
      $hoy = date('d/m/Y');
      $this->Cell(355, 10, utf8_decode($hoy), 0, 0, 'C'); // pie de pagina(fecha de pagina)
   }
}


$pdf = new PDF();
$pdf->AddPage(); /* aqui entran dos para parametros (horientazion,tamaño)V->portrait H->landscape tamaño (A3.A4.A5.letter.legal) */
$pdf->AliasNbPages(); //muestra la pagina / y total de paginas

$i = 0;
$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(163, 163, 163); //colorBorde

/* TABLA */
$idCita = 0;
foreach ($citas as $key => $cita){ 
    $i = $i + 1;
    if($idCita !== $cita->id){ 
            $total = 0;
            $pdf->SetFont('Arial', 'B', 12); //tipo fuente, cursiva, tamañoTexto
            $pdf->Cell(20, 10, $pdf->formatearHora($cita->hora), 1, 0, 'C', 0);
            $pdf->Cell(70, 10, utf8_decode($cita->cliente), 1, 0, 'C', 0);
            $pdf->Cell(55, 10, utf8_decode($cita->email), 1, 0, 'C', 0);
            $pdf->Cell(45, 10, utf8_decode($cita->telefono), 1, 1, 'C', 0);
            $pdf->SetFont('Arial', '', 12); //tipo fuente, cursiva, tamañoTexto
            $idCita = $cita->id;
    } //Fin del if  
    $pdf->Cell(65, 10, utf8_decode($cita->servicio . " - ") . $cita->precio . $EURO, 0, 1, 'L', 0);
    $total += $cita->precio;
    $actual = $cita->id;
    $proximo = $citas[$key+1]->id ?? 0;
    if(esUltimo($actual,$proximo)){ 
        $pdf->SetFont('Arial', 'B', 12); //tipo fuente, cursiva, tamañoTexto
        $pdf->Cell(20, 2, ("________________"), 0, 1, 'L', 0);
        $pdf->Cell(20, 10, ("Total: ". $total . $EURO), 0, 1, 'L', 0);
        $pdf->Cell(20, -6, ("________________"), 0, 1, 'L', 0);
        $pdf->Ln(15);
        $pdf->SetFont('Arial', '', 12); //tipo fuente, cursiva, tamañoTexto
    }
};//fin del foreach 
$pdf->Output('Citas_diarias-'.$pdf->formatearFecha($_POST['fecha']).'.pdf', 'I');//nombreDescarga, Visor(I->visualizar - D->descargar)






