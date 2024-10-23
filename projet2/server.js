// Initialize Sammy app
var app = Sammy('#content', function() {

    // Home page route
    this.get('#/', function() {
        $('#content').load('server/home_page.php');
    });


    // Help page route
    this.get('#/help', function() {
        $('#content').load('server/help_page.php');
    });


    // Login page route
    this.get('#/login', function() {
        $('#content').load('server/login_page.php', function() {
            $('#loginForm').submit(function(event) {
                event.preventDefault();
                var username = $('#username').val();
                var password = $('#password').val();
    
                $.ajax({
                    type: 'POST',
                    url: 'db/check-login.php',
                    data: { username: username, password: password },
                    success: function(response) {
                        if (response === 'success') {
                            sessionStorage.setItem('loggedIn', true);
                            window.location.hash = '#/help';
                        } else {
                            alert("Nom d'utilisateur ou mot de passe invalide.");
                        }
                    },
                    error: function() {
                        alert("Une erreur s'est produite lors du traitement de la connexion.");
                    }
                });
            });
        });
    });

    // Stats page route
    this.get('#/stats', function() {
                $('#content').load('server/stats_page.php');
        });

    //logout route
    this.get('#/logout', function() {
        $.ajax({
            type: 'POST',
            url: 'server/logout.php', 
            success: function(response) {
                sessionStorage.removeItem('loggedIn');
                sessionStorage.removeItem('username');
                window.location.hash = '#/login';
            },
            error: function() {
                alert('Error occurred while processing logout.');
            }
        });
    });


    // Dump facts page route
    this.get('#/dump/faits', function() {
        if (sessionStorage.getItem('loggedIn')) {
            $.ajax({
                url: 'jeux/api_rest.php',
                type: 'GET', 
                dataType: 'json',
                success: function(data) {
                    var records = data.records.map(function(record) {
                        return {
                            start: record.start,
                            rel: record.relation,
                            end: record.end
                        };
                    });
    
                    var tableHtml = '<table id="myTable" class="dataframe"><thead><tr><th>Start</th><th>Relation</th><th>End</th></tr></thead><tbody>';
                    records.forEach(function(record) {
                        tableHtml += '<tr><td>' + record.start + '</td><td>' + record.rel + '</td><td>' + record.end + '</td></tr>';
                    });
                    tableHtml += '</tbody></table>';  
                    $('#content').html(tableHtml);  
                    $('#myTable').DataTable();
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la récupération des données :', status, error);
                }
            });
        } else {
            window.location.hash = '#/login';
        }
    });
    

    //sign up page route
    this.get('#/sign', function() {
        $('#content').load('server/sign-page.php', function() {
            $('#registrationForm').submit(function(event) {
                event.preventDefault(); 
                var username = $('#username').val();
                var password = $('#password').val();
                    $.ajax({
                    type: 'POST',
                    url: 'db/check-username.php',
                    data: { username: username },
                    success: function(response) {
                        if (response === 'exists') {

                            $('#message').html('<p class="alert alert-danger">Username existe déja .</p>');
                        } else {

                            $('#message').html('');
                            $.ajax({
                                type: 'POST',
                                url: 'db/register_user.php',
                                data: { username: username, password: password },
                                success: function() {                                    
                                    window.location.hash = '#/login';
                                }
                            });
                        }
                    },
                    error: function() {
                        $('#message').html('<p class="alert alert-danger">Une erreur s\'est produite lors de la vérification du username .</p>');
                    }
                });
            });
        });
    });


    this.get('#/concept/:langue/:concept', function(context) {
        var langue = context.params.langue;
        var concept = context.params.concept;       
        var apiUrl = 'https://api.conceptnet.io/c/' + langue + '/' + concept + '?limit=1000';
        
        $.ajax({
            url: apiUrl,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var records = data.edges.map(function(edge) {
                    var startLang = edge.start.language;
                    var endLang = edge.end.language;
    
                    if (startLang !== langue || endLang !== langue) {
                        return null;
                    }
                    return {
                        start: edge.start.label,
                        rel: edge.rel.label,
                        end: edge.end.label
                    };
                });
    
                records = records.filter(function(record) {
                    return record !== null;
                });
    
                var tableHtml = '<table id="myTable" class="dataframe"><thead><tr><th>Start</th><th>Relation</th><th>End</th></tr></thead><tbody>';
                records.forEach(function(record) {
                    tableHtml += '<tr><td>' + record.start + '</td><td>' + record.rel + '</td><td>' + record.end + '</td></tr>';
                });
                tableHtml += '</tbody></table>';
                $('#content').html(tableHtml);
                $('#myTable').DataTable();
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de la récupération des données:', status, error);
            }
        });
    });
    
    
    

    
    this.get('#/relation/:relation/from/:langue/:concept', function(context) {
        var relation = context.params.relation;
        var langue = context.params.langue;
        var concept = context.params.concept;
        var apiUrl = 'https://api.conceptnet.io/c/' + langue + '/' + concept + '?limit=1000';

    $.ajax({
        url: apiUrl,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var records = data.edges.map(function(edge) {
            var startLang = edge.start.language;
            var endLang = edge.end.language;
            var edgeRelation = edge.rel.label; 

            if (startLang !== langue || endLang !== langue || edgeRelation !== relation) {
                return null;
            }

            return {
                start: edge.start.label,
                rel: edgeRelation,
                end: edge.end.label
            };
            });

            records = records.filter(function(record) {
                return record !== null;
            });

            var tableHtml = '<table id="myTable" class="dataframe"><thead><tr><th>Start</th><th>Relation</th><th>End</th></tr></thead><tbody>';
            records.forEach(function(record) {
                tableHtml += '<tr><td>' + record.start + '</td><td>' + record.rel + '</td><td>' + record.end + '</td></tr>';
            });
            tableHtml += '</tbody></table>';
            $('#content').html(tableHtml);
            $('#myTable').DataTable();
        },
        error: function(xhr, status, error) {
            console.error('Erreur lors de la récupération des données:', status, error);
    
        }
        });
    });
    

    this.get('#/relation/:relation', function(context) {
        var relation = context.params.relation;
        var apiUrl = 'https://api.conceptnet.io/c/fr/?limit=1000';

        $.ajax({
            url: apiUrl,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var records = data.edges.map(function(edge) {
                var edgeRelation = edge.rel.label; 

                if (edgeRelation !== relation) {
                    return null;
                }

                return {
                    start: edge.start.label,
                    rel: edgeRelation,
                    end: edge.end.label
                };
            });
            records = records.filter(function(record) {
                return record !== null;
            });

            var tableHtml = '<table id="myTable" class="dataframe"><thead><tr><th>Start</th><th>Relation</th><th>End</th></tr></thead><tbody>';
            records.forEach(function(record) {
                tableHtml += '<tr><td>' + record.start + '</td><td>' + record.rel + '</td><td>' + record.end + '</td></tr>';
            });
            tableHtml += '</tbody></table>';
            $('#content').html(tableHtml);
            $('#myTable').DataTable();
        },
        error: function(xhr, status, error) {
            console.error('Erreur lors de la récupération des données:', status, error);
            }
       });
    });

    //les cas par défaut
    this.get('#/jeux/quisuisje/', function(context) {
        context.redirect('#/jeux/quisuisje/60/10');
    });


    this.get('#/jeux/quisuisje/:temps/:indice', function(context) {
        var temps = context.params.temps;
        var indice = context.params.indice;

        $.ajax({
            url: 'jeux/jeu1.php',
            type: 'GET', 
            data: {
            temps: temps,
            indice: indice
        },
        success: function(response) {
            $('#content').html(response);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
     });
    });


    this.get('#/jeux/related/', function(context) {
        context.redirect('#/jeux/related/60');
    });
    this.get('#/jeux/related/:temps', function(context) {
        var temps = context.params.temps;

        $.ajax({
            url: 'jeux/jeu2.php',
            type: 'GET',
            data: {
                temps: temps
            },
            success: function(response) {
                $('#content').html(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    this.get('#/jeux/related/', function(context) {
        var temps = 60;

        $.ajax({
            url: 'jeux/jeu2.php',
            type: 'GET', 
            data: {
                temps: temps
            },
        success: function(response) {
            $('#content').html(response);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
    });
});


    
$(function() {
    app.run('#/');
});
