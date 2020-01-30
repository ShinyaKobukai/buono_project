(function(){
    document.querySelector('#btn').onclick = function(){
      var food_name = document.form1.food_name.value;
      document.querySelector('#food_name').textContent = food_name;
      if (!food_name.match(/\S/g)){
        alert('記述に問題があります。訂正してください。');
        return false;
      } 
    }
  })();