<?php

namespace app\base;


class TipayRequestForm 
{
    public static function render($fieldValues, $paymentUrl)
    {
        
        echo "<div id='loader' style='display: none'>Loading...</div>";
        echo "<div id='content'></div>";
        echo "<form id='autosubmit' action='".$paymentUrl."' method='post'>";
        if (is_array($fieldValues) || is_object($fieldValues))
        {
            foreach ($fieldValues as $key => $val) {
                echo "<input type='hidden' name='".ucfirst($key)."' value='".htmlspecialchars($val)."'>";
            }
        }
        echo "</form>";
        echo "
		<script type='text/javascript'>

const loader = document.querySelector('#loader')
const content = document.querySelector('#content')
async function fetchData() {
 return new Promise(resolve =>document.getElementById('autosubmit').submit())
}	

(async() => {
   content.innerHTML = ''
  // Your loader styling, mine is just text that I display and hide
  loader.style.display = 'block'
  const nextContent =await fetchData()  
  loader.style.display = 'none'
  content.innerHTML = nextContent
})()
</script>
";       
    }
}
