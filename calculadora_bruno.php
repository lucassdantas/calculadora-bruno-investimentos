<?php
/**
* Plugin Name: Calculadora Bruno
* Plugin URI: https://github.com/lucassdantas/calculadora-bruno-investimentos
* Description: Calculadora para o site do Buno Investimentos
* Version: 0.5
* Author: R&D Marketing Digital
* Author URI: https://rdmarketing.com.br/
**/

defined('ABSPATH') or die("You dont have permissions to execute that.");
if(!function_exists('add_action')){
    die;
}
function costScript(){
	?>

<script>
let waitForElementToExist = (selector) => {
  return new Promise(resolve => {
    if (document.querySelector(selector)) {
      return resolve(document.querySelector(selector));
    }

    const observer = new MutationObserver(() => {
      if (document.querySelector(selector)) {
        resolve(document.querySelector(selector));
        observer.disconnect();
      }
    });

    observer.observe(document.body, {
      subtree: true,
      childList: true,
    });
  });
}, 
calcBtn = document.querySelector(".calcBtn")
calcBtn.addEventListener('mousedown', () => {
  /*  
  waitForElementToExist('.resultValue .jet-form__calculated-field-input').then(element => {
      element.innerHTML = element.innerHTML.replace(".", ",")
  });

  waitForElementToExist('.marketResultValue .jet-form__calculated-field-input').then(element => {
      element.innerHTML = element.innerHTML.replace(".", ",")
  });*/
  //let 
    //result = document.querySelector(".resultValue .jet-form__calculated-field-input").innerHTML,
    //marketResult = document.querySelector(".marketResultValue .jet-form__calculated-field-input").innerHTML,
    tax = document.querySelector("#tax").value,
    marketTax = document.querySelector("#market_tax").value,
    economyValue = document.querySelector(".economyValue .jet-form__calculated-field-val").innerHTML,
    valueInput = document.querySelector("#applicationValue").value,
    segment = document.querySelector("#segment").value,
    wpp = document.querySelector("#wpp").value,
    email = document.querySelector("#email").value
    let message = `
      Investimento: R$${valueInput}, <br>
      E-mail: ${email}, <br>
      WhatsApp: ${wpp}, <br> 
      Segmento: ${segment}, <br>
      Taxa ao ano: ${result}, <br>
      Valor que seria pago na média de mercado: R$${marketResult}, <br>
      Conosco você economiza: R$${economyValue}, <br>
    `
  jQuery(function($){

    
    jQuery.ajax({
      url: "<?php echo admin_url('admin-ajax.php'); ?>",
      type: "POST",
      cache: false,
      data:{ 
          action: 'send_email', 
          name: "Calculo do investimento",
          email: email,
          message: message,
      },
      success:function(res){
        console.log(res);
      }
    }); 
  });
})
</script>

<?php
}
add_action('wp_footer', 'costScript');

add_action( 'wp_ajax_send_email', 'callback_send_email' );
add_action( 'wp_ajax_nopriv_send_email', 'callback_send_email' );
function callback_send_email(){
    include './emails.php';
    
	$name = $_REQUEST['name'];
	$email = $_REQUEST['email'];
	$message= $_REQUEST['message'];
	$subject = "Calculo de investimento";
	$email_body = "Dados do cliente: <br> $message. <br>";
	$to = $receiver;
	$headers  = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= "From: BrunoTavares <$email> \r\n";
	$headers .= "Reply-To: $email \r\n";
	$headers .= "Bcc: $email, $copyReceiver";
	$mail = mail($to,$subject,$email_body,$headers);
	if($mail){
		echo "Email enviado com sucesso.";
	}	
 }