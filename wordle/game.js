    guessNum = 0;

    let wordList = [];
    let chosenWord = '';

    function getGuess(guessNum) {
        //select inputs
        const guessInputs = document.querySelectorAll(`.row-${guessNum + 1} input`);
        const guessWord = Array.from(guessInputs).map(input => input.value);
        const guessWordStr = guessWord.join('');  // Join array into a single string
        console.log('Guess Word:', guessWordStr); // Debugging guessWordStr
        return guessWord;  // Return the string instead of the array
    }

    function initializeGame() {
        //get random word and then run function to add all event listeners etc.
        fetch('https://api.datamuse.com/words?sp=?????&max=100&md=f:100000')
        .then(response => response.json())
        .then(data => {
            wordList = data.map(word => word.word);
            chosenWord = wordList[Math.floor(Math.random() * wordList.length)].toLowerCase();
            console.log("Chosen word:", chosenWord);

            setupGame();
        })
        .catch(error => console.error('Error fetching words:', error));
    }

    function findKeyButtonByValue(value) {
        //look through key elements for matching value
        const keys = document.querySelectorAll('.key');
        for (let key of keys) {
            if (key.textContent.toLowerCase() == value) {
                return key;
            }
        }
        return null;
    }

    function wrongFlash(rowNum) {
        //flash red when wrong, make so other functions wait for this to finish
        return new Promise((resolve) => {
            const inputs = document.querySelectorAll(`.row-${rowNum} input`);

            inputs.forEach(input => {
                input.style.backgroundColor = 'lightcoral';
            });

            setTimeout(() => {
                inputs.forEach(input => {
                    input.style.backgroundColor = 'white';
                });
                resolve();
            }, 500);
        });
    }

    function setupGame() {
        //select inputs for later use
        const inputs = document.querySelectorAll('.grid-item input');

        //add listeners for typing/deleting letters
        inputs.forEach((input, index) => {
            input.addEventListener('input', function () {
                //move to the next input (to the right)
                if (this.value.length === 1) {
                    const nextInput = inputs[index + 1];
                    if (nextInput) {
                        nextInput.focus();
                    }
                }
            });
            input.addEventListener('keydown', function (event) {
                if (event.key === 'Backspace') {
                    // Move to the previous input if it's not the first one
                    if (index > 0) {
                        const prevInput = inputs[index - 1];
                        prevInput.focus();
                        prevInput.value = '';
                    }
                }
            });
        });

        //create keyboard
        const keyboardDiv = document.getElementById('keyboard');
        const keyboardLayout = [
            ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P'],
            ['A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L'],
            ['Z', 'X', 'C', 'V', 'B', 'N', 'M']
        ];

        //add necessary characteristics to keys
        keyboardLayout.forEach((row, rowIndex) => {
            //for better formatting
            if (rowIndex == keyboardLayout.length - 1) {
                    spacer = document.createElement('div');
                    spacer.classList.add('spacer');
                    keyboardDiv.appendChild(spacer);
                    spacer = document.createElement('div');
                    spacer.classList.add('spacer');
                    keyboardDiv.appendChild(spacer);
            }
            row.forEach(keyChar => {
                const keyButton = document.createElement('div');
                keyButton.classList.add('key');
                
                keyButton.textContent = keyChar;
                keyboardDiv.appendChild(keyButton);
            });
        });

        //get button
        submitter = document.getElementsByTagName("button")[0];

        // add function that triggers on guess submit
        submitter.addEventListener('click', async function checkGuess() {
            guess = getGuess(guessNum);
            //condition: won game
            if (guess.join('').toLowerCase() == chosenWord) {
                //show win popup
                const modal = document.getElementById("myModal");
                modal.style.display = "flex";
                party.confetti(submitter, {
                    count: party.variation.range(20, 40)
                });
                const modalBtn = document.getElementById("modalBtn");
                modalBtn.addEventListener("click", function() {
                    //reload the page
                    location.reload();
                });

            } else if (guessNum < 5) { //condition: game incomplete
                await wrongFlash(guessNum + 1);
                //wait for wrong flash then color keyboard and inputs
                guess.forEach((letter, index) => {
                    letter = letter.toLowerCase();
                    const inputElement = document.querySelectorAll(`.row-${guessNum + 1} input`)[index];
                    
                    if (chosenWord.split('').includes(letter)) {
                        if (index == chosenWord.indexOf(letter)) {
                            findKeyButtonByValue(letter).style.backgroundColor = 'lightgreen';
                            inputElement.style.backgroundColor = 'lightgreen';
                        } else {
                            findKeyButtonByValue(letter).style.backgroundColor = 'lightyellow';
                            inputElement.style.backgroundColor = 'lightyellow';
                        }
                    } else {
                        findKeyButtonByValue(letter).style.backgroundColor = 'darkgrey';
                        inputElement.style.backgroundColor = 'darkgrey';
                    }
                })
                guessNum += 1;
            } else {
                //popup code
                const modalContent = document.getElementsByClassName('modal-content')[0];
                const h2Element = modalContent.getElementsByTagName('h2')[0];
                const pElement = modalContent.getElementsByTagName('p')[0];
                h2Element.textContent = "You lose!";
                pElement.textContent = "Try again?";
                const emojiContainer = document.getElementById('emojiContainer');
                emojiContainer.textContent = '😞';
                emojiContainer.style.padding = '1vw';
                const modal = document.getElementById("myModal");
                modal.style.display = "flex";
                const modalBtn = document.getElementById("modalBtn");
                modalBtn.addEventListener("click", function() {
                    //reload the page
                    location.reload();
                });

            }
        })
    }

    initializeGame()