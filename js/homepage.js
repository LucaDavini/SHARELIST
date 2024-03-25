/* Changing curiosities about shopping lists */
var curiosities = [
    'Una lista della spesa ben fatta riduce gli sprechi – perchè compri solo quello che effettivamente mangerai – e nel lungo periodo permette un consistente risparmio sulla spesa alimentare.',
    'La lista della spesa condivisa è uno strumento molto utile che permette di risparmiare tempo e di comprare il giusto senza sprechi, dividendosi le varie mansioni tra i membri della famiglia o tra gli amici.',
    'Secondo i dati diffusi dalla Coldiretti, metà dei cittadini italiani (49,8%) va a fare la spesa portando con sé una lista: un’ottima abitudine per scongiurare acquisti d’impulso ed evitare sprechi alimentari.'
];
var curiosity_number = 0;

function changeCuriosity (direction) {
    var c = document.getElementById('curiosity').getElementsByTagName('p')[1];
    
    if (direction == 'L')
        curiosity_number = (curiosity_number + 2) % 3;      // scorre l'array al contrario
    else if (direction == 'R')
        curiosity_number = (curiosity_number + 1) % 3;
    else
        return;
    
    c.textContent = curiosities[curiosity_number];
}


/* Showing the project's description */
var isDescVisible = false;

function showDesc () {
    var descVisualizer = document.getElementById('descVisualizer');
    var desc = document.getElementById('desc');

    if (!isDescVisible)
    {
        descVisualizer.textContent = 'Nascondi Descrizione';
        desc.style.display = 'block';
    }
    else
    {
        descVisualizer.textContent = 'Visualizza Descrizione';
        desc.style.display = 'none';
    }

    isDescVisible = !isDescVisible;
}