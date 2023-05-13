
<?= $this->render ( 'header.php' );?>

  <!--- body start-->
            <tr>
               <td style="padding: 30px 20px 20px 20px;background: #fff;" colspan="2" align="left">
                  <p  style="margin: 10px 0; font-size: 16px; font-weight: bold; line-height: 1.6;color: #333;">
                     Hi Smiles Davis,                        
                  </p>
                  <p style="margin: 20px 0; font-size: 16px; line-height: 1.6; color: #333;">
                  	<?php echo $content?> 
                  </p>
                
                  
               </td>
            </tr>
  <!--body end-->
<?= $this->render ( 'footer.php' );?>