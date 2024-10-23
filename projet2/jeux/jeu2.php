<?php 

$temps = $_GET['temps'];
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
        "car", "dentist", "banana", "music", "airplane",
        "television", "guitar", "piano", "hospital", "telephone",
        "restaurant", "basketball", "internet", "store", "pizza",
        "robot", "star", "video game", "park", "bicycle"
    ];

    const jeu2 = $('#jeu2');
    const feedback = $('.feedback', jeu2);
    const jeu2Question = $('#jeu2_question', jeu2);
    const responsejeu2 = $('#reponsejeu2', jeu2);
    const scoreDisplay = $('.score', jeu2);
    const timerDisplay = $('.chronometre', jeu2);
    let currentFacts = [];
    let currentConcept = '';
    let timer = <?php echo json_encode($temps); ?>;   
    let timerset = <?php echo json_encode($temps); ?>; 
    let intervalHint;
    let intervalTimer;
    let numHintsGiven = 0;
    let correctAnswer = '';
    let relatedWords ='';
    let unrelatedWords='';

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
            endGame2(relatedWords,unrelatedWords);
        }
    }

    function removeAccents(str) {
        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    }

    function resetGame() {
        currentConcept = '';
        relatedWords = '';
        unrelatedWords = '';
        numHintsGiven = 0;
        feedback.text('');
        responsejeu2.val('');
    }

    function startGame2(x) {
        resetGame();
        loadFacts().then(() => {
            selectConcept();
            timer = x;
            clearInterval(intervalTimer);
            intervalTimer = setInterval(() => updateTimer('jeu2'), 1000);
            displayConcept();
            $('.submitReponse').show(); 
        });
    }

    function checkWordRelation(words) {
        let relatedWords = [];
        let unrelatedWords = [];

        words.forEach(word => {
            let related = false;
            word = removeAccents(word);
            currentFacts.forEach(fact => {
                if (fact.start.toLowerCase() === currentConcept.toLowerCase() && fact.end.toLowerCase() === word.toLowerCase()) {
                    relatedWords.push(word);
                    related = true;
                } else if (fact.end.toLowerCase() === currentConcept.toLowerCase() && fact.start.toLowerCase() === word.toLowerCase()) {
                    relatedWords.push(word);
                    related = true;
                }
            });
            if (!related) {
                unrelatedWords.push(word);
            }
        });

        endGame2(relatedWords, unrelatedWords);
        responsejeu2.val('');
    }

    function displayConcept() {
        jeu2Question.text(`Entrez des mots liés au concept : ${currentConcept}`);
    }

    function endGame2(related, unrelated) {
        let resultText = "";
        clearInterval(intervalTimer);
        clearInterval(intervalHint);

        if (related.length > 0) {
            resultText += `Mots liés : ${related.join(', ')}<br>`;
        }
        else{
            resultText += `Pas de mots liés<br>`;
        }

        if (unrelated.length > 0) {
            resultText += `Mots non liés : ${unrelated.join(', ')}<br>`;
        }
        else{
            resultText += `Pas de mots non liés<br>`;
        }

        let score = related.length;
        feedback.html(resultText);
        scoreDisplay.text(score);
        $.ajax({
            url: 'jeux/update_score2.php', 
            type: 'GET',
            data: { score: score },
            success: function(response) {
                console.log('Score mis à jour avec succès !');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Erreur lors de la mise à jour du score 2 :', errorThrown);
            }
        });
        $('.submitReponse').hide(); 
    }

    $('.submitReponse').click(function(event) {
        event.preventDefault();
        const userInput = responsejeu2.val().split(',').map(word => word.trim());
        checkWordRelation(userInput);
    });

    $('#formjeu2').submit(function(event) {
        event.preventDefault();
        const userInput = responsejeu2.val().split(',').map(word => word.trim());
        checkWordRelation(userInput);
    });

    $('#btnRejouer2').click(function() {
        resetGame();
        startGame2(timerset);
    });

    startGame2(timer);
});


</script>

    <div class="jeux" id="jeu2">
        <h2>Related !</h2>
        <form id="formjeu2">
            <p class="titre-style" id="jeu2_question"></p>
            <input id="reponsejeu2" type="text" required />
            <br>
            <button type="submit" class="submit btn btn-primary submitReponse" data-jeu="jeu2">Soumettre</button>
        </form>

        <div class="feedback"></div>
        <div class="jeu-footer">
            <div>Chronomètre: <span class="chronometre"><?php echo intval($temps); ?></span> secondes</div>
            <div>Score: <span class="score">0</span></div>
            <button id="btnRejouer2" class="btn btn-primary" >Rejouer</button>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@200;400;600;700&display=swap" rel="stylesheet">

    

    