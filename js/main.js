function scorePassword(pass) {
    var score = 0;
    if (!pass)
        return score;
    score = pass.length *3
    // bonus points for mixing it up
    var variations = {
        digits: /\d/.test(pass),
        lower: /[a-z]/.test(pass),
        upper: /[A-Z]/.test(pass),
        nonWords: /\W/.test(pass),
        mixedWords: /\w[^\s\w]/.test(pass),
        blanks: /\s/.test(pass)
    }
    variationCount = 0;
    for (var check in variations) {
        variationCount += (variations[check] == true) ? 1 : 0;
    }
    score += score*(variationCount-1) ;

    return parseInt(score);
}

function checkPassStrength(score) {
    if (score > 9000)
        return "It's over 9.000!!!";
    if (score > 1200)
        return "impenetrable";
    if (score > 320)
        return "great!";
    if (score >160)
        return "strong";
    if (score > 80)
        return "good";
    if (score > 60)
        return "average";
    if (score >= 30)
        return "weak";

    return "";
}

function requirements(pass){
    var variations = {
        //nondigit: /\D/.test(pass),
        //aletter: /[A-Za-z]/.test(pass),
        //nletter: /[^A-Za-z]/.test(pass),
        //eightchar: /.{8}/.test(pass),
    }
    for (var check in variations) {
      if (variations[check] != true){
        return false;
      }
    }
    return true;
}

function setPassStrengthMeter(){
  let square = document.querySelector('input[name="pwd-square"]');
  let circle = document.querySelector('input[name="pwd-circle"]');
  let triangle = document.querySelector('input[name="pwd-triangle"]');
  let meter = document.querySelector('meter');
  let out = document.querySelector('output');
  let test = square.value+circle.value+triangle.value;
  let score = scorePassword(test);
  console.log([square, circle, triangle, meter, out, score]);
  meter.value = score;
  let ok = requirements(test)
  if (ok){
    out.value = checkPassStrength(score);
    document.querySelector("button#register").disabled=false;
  }else{
    out.value = "too weak";
    document.querySelector("button#register").disabled=true;
  }
}

function chooseOne(caller, ids){
  let displays = ['none','block' ];
  let first = 1;
  for (let i = 0; i< ids.length; i++){
    el = document.querySelector(ids[i]);
    el.style.display = displays[first];
    first = 0;
  }
}
