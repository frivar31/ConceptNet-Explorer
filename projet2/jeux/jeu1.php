<?php
$temps = $_GET['temps'];
$indice = $_GET['indice'];
session_start();
if (!isset($_SESSION['username'])) {
    echo "<p>Vous devez vous connecter à <a href='#/login'>login</a>.</p>";
    exit;
}

echo "<p><b>Joueur, " . $_SESSION['username'] . "!</b></p>";
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

$(document).ready(function() {

  const concepts_fr = [
      "maison", "chat", "forêt", "théâtre", "montre",
      "chaussures", "cinéma", "hôpital", "voyage", "photographie",
      "jardin", "horloge", "feu", "bibliothèque", "hôtel",
      "océan", "football", "épicerie", "café", "cirque"
  ];

  const concepts_en = [
      "voiture", "dentiste", "banane", "musique", "avion",
      "télévision", "guitare", "piano", "hôpital", "téléphone",
      "restaurant", "basketball", "internet", "magasin", "pizza",
      "robot", "étoile", "jeu vidéo", "parc", "bicyclette"
  ];

  const jeu1 = $('#jeu1');
  const feedback = $('.feedback', jeu1);
  const jeu1Question = $('#jeu1_question', jeu1);
  const responseJeu1 = $('#reponseJeu1', jeu1);
  const scoreDisplay = $('.score', jeu1);
  const timerDisplay = $('.chronometre', jeu1);
  let currentFacts = [];
  let currentConcept = '';
  let timer = <?php echo json_encode($temps); ?>;   
  let hintTimer = <?php echo json_encode($indice); ?>;
  let timerset = <?php echo json_encode($temps); ?>;   
  let hintTimerset = <?php echo json_encode($indice); ?>;
  let intervalHint;
  let intervalTimer;
  let numHintsGiven = 0;
  let correctAnswer = '';

  function loadFacts() {
    return $.ajax({
        url: 'jeux/score_api.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            currentFacts = data.records;
        },
        error: function(jqXHR, textStatus, errorThrown) {

            $.getJSON('jeux/facts.json', function(data) {
                currentFacts = data.records;
            });
        }
    });
    }

  function selectConcept() {
      const combinedConcepts = [...concepts_en, ...concepts_fr];
      currentConcept = combinedConcepts[Math.floor(Math.random() * combinedConcepts.length)];
      correctAnswer = currentConcept;
  }

  function updateTimer() {
      timer--;
      timerDisplay.text(timer);
      if (timer <= 0) {
        endGame(false);
      }
  }

  function removeAccents(str) {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
  }

  function startGame(x, y) {
    loadFacts().then(() => {
        $('#submitbtn').show(); 
        currentConcept = '';
        selectConcept();
        numHintsGiven = 0;
        timer = x;
        hintTimer = y;
        clearInterval(intervalTimer);
        clearInterval(intervalHint);
        intervalTimer = setInterval(() => updateTimer(), 1000);
        intervalHint = setInterval(displayHint, hintTimer * 1000);
        displayFirstHint();
        feedback.text('');
        scoreDisplay.text('0');
    });
  }

  function displayFirstHint() {
    const fact = currentFacts.find(f => f.start.toLowerCase() === currentConcept.toLowerCase() || f.end.toLowerCase() === currentConcept.toLowerCase());
    if (fact) {
        const hint = fact.start.includes(currentConcept) ? `??? ${fact.relation} ${fact.end}` : `${fact.start} ${fact.relation} ???`;
        jeu1Question.text(hint);
        numHintsGiven++;
    }
  }

  function displayHint() {
      const factsRelated = currentFacts.filter(f => f.start.toLowerCase() === currentConcept.toLowerCase() || f.end.toLowerCase() === currentConcept.toLowerCase());
      const hintIndex = numHintsGiven % factsRelated.length;
      const fact = factsRelated[hintIndex];
      if (fact) {
          const hint = fact.start.includes(currentConcept) ? `??? ${fact.relation} ${fact.end}` : `${fact.start} ${fact.relation} ???`;
          jeu1Question.text(hint);
          numHintsGiven++;
      }
  }

  function checkAnswer() {
      const userInput = removeAccents(responseJeu1.val().trim().toLowerCase());
      const currentConceptWithoutAccents = removeAccents(currentConcept.toLowerCase());
      if (userInput === currentConceptWithoutAccents.toLowerCase()) {
          endGame(true);
      } else {
          feedback.text('Incorrect, essayez à nouveau !'); 
      }
      responseJeu1.val('');
  }

  function endGame(won) {
    clearInterval(intervalTimer);
    clearInterval(intervalHint);
    if (won) {
        feedback.html('Félicitations ! Vous avez gagné !<br>La réponse correcte était : ' + correctAnswer);
    } else {
        feedback.html('Le temps est écoulé. Fin du jeu !<br>La réponse correcte était : ' + correctAnswer);
    }
    let score = Math.ceil(timer / hintTimer) - numHintsGiven;

    $.ajax({
        url: 'jeux/update_score1.php',
        type: 'GET',
        data: { score: score }, 
        success: function(response) {
            console.log('Score mis à jour avec succès !');
        
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Erreur lors de la mise à jour du score : (jeu)', errorThrown);
        }
    });
    scoreDisplay.text(score);
    $('#btnRetourMenu, #btnRejouer').show();
    $('#submitbtn').hide(); 
  }

  $('#submitbtn').click(function(event) {
    event.preventDefault();
    checkAnswer();
    });

  $('#formJeu1').submit(function(event) {
      event.preventDefault();
      checkAnswer();
  });

  $('#btnRejouer').click(function() {
    startGame(timerset,hintTimerset);
  });

  startGame(timer,hintTimer);
});

</script>
    <div class="jeux" id="jeu1">
        <h2>Qui suis-je ?</h2>
        <form id="formJeu1">
            <p class="titre-style" id="jeu1_question"></p>
            <input id="reponseJeu1" type="text" required />
            <br>
            <button type="submit" id ="submitbtn"class="submit btn btn-primary submitReponse" data-jeu="jeu1">Vérifier</button>
        </form>

        <div class="feedback"></div>
        <div class="jeu-footer">
            <div>Chronomètre: <span class="chronometre"><?php echo intval($temps); ?></span> secondes</div>
            <div>Score: <span class="score">0</span></div>
            <button id="btnRejouer" class="btn btn-primary" >Rejouer</button>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@200;400;600;700&display=swap" rel="stylesheet">


    


    