class Pokemon {
	constructor(name, maxHP, curHP, attackStrength, attackSpeed){
	this.name = name;
	this.maxHP = maxHP;
	this.curHP = curHP;
	this.strength = attackStrength;
	this.attackSpeed = attackSpeed;
	}
	usePotion(){
		this.curHP += 50;
		if(this.curHP > this.maxHP){
			this.curHP = this.maxHP;
		}
	}
}

class Sprite {
	constructor(img, leftRight, ogWidth, ogHeight, adjWidth, adjHeight, startX, startY, curX, curY, sound){
		this.img = new Image;
		this.img.src = img;
		this.leftRight = leftRight; // Whether pokemon is on left or right, determines px vals
		this.origW = ogWidth;
		this.origH = ogHeight;
		this.adjW = adjWidth;
		this.adjH = adjHeight;
		this.startX = startX;
		this.startY = startY;
		this.curX = curX;
		this.curY = curY;
		this.sound = new Audio(sound);
		this.coef = 1;
	}
	startPos(){ // get sprite to start postion
		context.drawImage(this.img, 0, 0, this.origW, this.origH, this.startX, this.startY, this.adjW, this.adjH);
	}
	attacking(){ // animation of pokemon attacking
		coef = 1;
		clearX1 = 400;
		if(this.leftRight == "right"){
			coef = -1; // if "left" = 1
			clearX1 = 1000;
		}
		this.sound.play();
		lastmove = 0;
		speed = 100;
		stopSprite();
		animationInterval = setInterval(() => {
			context.clearRect(clearX1, clearY1, clearX2, clearY2);
			if(lastmove == 0){
				this.curX = this.curX+(coef*30);
				this.curY = this.curY-5;
				context.drawImage(this.img, 0, 0, this.origW, this.origH, this.curX, this.curY, this.adjW, this.adjH);
				lastmove = 1;
			}
			else if(lastmove==1){
				this.curX = this.curX+(coef*30);
				this.curY = this.curY+5;
				context.drawImage(this.img, 0, 0, this.origW, this.origH, this.curX, this.curY, this.adjW, this.adjH);
				lastmove = 2;
			}
			else if(lastmove == 2 || lastmove == 3){
				this.curX = this. curX-(coef*30);
				context.drawImage(this.img, 0, 0, this.origW, this.origH, this.curX, this.curY, this.adjW, this.adjH);
				lastmove = lastmove + 1;
			}
			else {
				stopSprite();
				this.curX = this.startX;
				this.curY = this.startY;
				context.drawImage(this.img, 0, 0, this.origW, this.origH, this.curX, this.curY, this.adjW, this.adjH);
			}
		}, speed);
	}
	hurting(){ // animation of pokemon hurting
		coef = 1;
		clearX1 = 400;
		if(this.leftRight == "right"){
			coef = -1; // if "left" = 1
			clearX1 = 1100;
		}
		lastmove = 0;
		speed = 200;
		stopSprite();
		animationInterval = setInterval(() => {
			context.clearRect(clearX1, clearY1, clearX2, clearY2);
			if(lastmove < 6 && lastmove % 2 == 0 ){
				context.drawImage(this.img, 0, 0, this.origW, this.origH, this.curX, this.curY, this.adjW, this.adjH);
				lastmove = lastmove + 1;
			}
			else if (lastmove < 6) {
				lastmove = lastmove + 1;
			}
			else {
				stopSprite();
				this.curX = this.startX;
				this.curY = this.startY;
				context.drawImage(this.img, 0, 0, this.origW, this.origH, this.curX, this.curY, this.adjW, this.adjH);
			}
		}, speed);
	}
	fainting(){ // animation of pokemon fainting
		clearX1 = 400;
		if(this.leftRight == "right"){
			clearX1 = 1100;
		}
		faint.play();
		stopSprite();
		speed = 100;
		animationInterval = setInterval(() => {
			context.clearRect(clearX1, clearY1, clearX2, clearY2);
			if(this.curY < faintHeight){
				this.curY = this.curY + 10;
				context.drawImage(this.img, 0, 0, this.origW, this.origH, this.curX, this.curY, this.adjW, this.adjH);
			}
			else {
				stopSprite();
			}
		}, speed);	
	}
}

class Team {
	constructor(teamSize, array){
		this.numMons = teamSize;
		this.numLeft = teamSize;
		this.monArray  = [];
		for (let i = 0; i < this.numMons; i++){
			this.monArray[i] = array[i];
		}
	}
	swapPokemon(num){
		return this.monArray[num];
	}
}

// Create player team, pokemon and sprites
const player1 = new Pokemon("Squirtle", 200, 200, 50, 60);
var playerArray = [player1];
var playerTeam = new Team(1, playerArray);
var myMon = playerTeam.swapPokemon(0);

//Player Sprites
var left1 = new Sprite('007squirtle_reversed.png', "left", 600, 600, 200, 200, 400, 450, 400, 450, 'cut.wav');
var playerSprites = [left1];
var leftMon = playerSprites[0];

// Enemy Team Pokemon
const enemy1 = new Pokemon("Vileplume", 80, 80, 82, 85);
var enemyArray = [enemy1];
var enemyTeam = new Team(1, enemyArray);
var enemy = enemy1;
var curEnemy = 0;


// Enemy team sprites
var right1 = new Sprite('pokemonImg/045Vileplume.png', "right", 600, 600, 200, 200, 1200, 450, 1200, 450, 'scratch.wav');
var enemySprites = [right1];
var rightMon = enemySprites[0];
var curEnemySprite = 0;

//Pokeball sound & sprite-840x657
const pokeCatch = new Audio('catchPokemon.mp3');
const ballImg = new Image;
ballImg.src = 'pokeball.png';

// Button variables
var promptText = document.getElementById("prompt");
var button1 = document.getElementById("button1");
var button3 = document.getElementById("button3");
var button5 = document.getElementById("button5");
var button2 = document.getElementById("button2");
var button4 = document.getElementById("button4");
var button6 = document.getElementById("button6");


// vars used to reset sprites	
var lastmove = 0;
var speed = 100;
var coef = 1;
var clearX1 = 400;
const clearX2 = 400;
const clearY1 = 350;
const clearY2 = 395;
const faintHeight = 535;

// Get canvas and set text height
var canvas = document.getElementById("myCanvas");
var context = canvas.getContext('2d');
context.font = "30px Arial";

// Health bar sprite
let health = new Image;
health.src = 'health_bar.png';
var pokeballCount = jsonData.wallet.pokeballs;
var potionCount = jsonData.wallet.heal_potion;

// declare animation Interval
let animationInterval;

// player health bar
const origStatWidth = 889;
const origStatHeight = 309;
const statWidth = 400;
const statHeight = 100;
const statX = 350;
const statY = 700;

// Audio
var bgMusic = new Audio('battle_music.mp3');
var faint = new Audio('faint.mp3');

// calls init to start animation
onload = function() {
	promptText.innerHTML="Ready to Explore?"
	button1.innerHTML = "Yes";
	button2.innerHTML = "No";
	button1.onclick = function(){
		bgMusic.play();
		init();}
	// leave if button 2
	button2.onclick = function(){ window.location.replace("../play/worldMap.html");}
}

function stopSprite(){
		clearInterval(animationInterval);
}

function init(){
	// Put sprites on canvas 
	leftMon.startPos();
	rightMon.startPos();
	// Add pokemon names and health to stat bar
	context.fillText(myMon.name, 350, 770);
	context.fillText(enemy.name, 1185, 770);
	context.drawImage(health, 0, 0, 662, 64, 435, 789, 178, 12);
	context.drawImage(health, 0, 0, 662, 64, 1273, 790, 179, 12);
	// enter the game function
	gameLoop();
}

function gameLoop(){
	
	// Main set of prompts
	promptText.innerHTML="A wild " + enemy.name + " appeared!";
	button1.innerHTML = "Fight";
	button2.innerHTML = "Pokemon";
	button3.innerHTML = "Bag";
	button4.innerHTML = "Run";
	button5.innerHTML = " ";
	button6.innerHTML = " ";
			
	// User input to other functions
	button1.onclick = function(){fight();}
	button2.onclick = function(){viewPokemon();}
	button3.onclick = function(){viewBag();}
	button4.onclick = function(){run();}
}


function updateLeftHealth(){
	context.clearRect(435, 789, 435+179, 802);
	var percent = myMon.curHP / myMon.maxHP;
	context.drawImage(health, 0, 0, 662*percent, 64, 435, 789, 178*percent, 12);
}

function updateRightHealth(){
	context.clearRect(1273, 790, 1273+179, 802);
	var percent = enemy.curHP / enemy.maxHP;
	context.drawImage(health, 0, 0, 662*percent, 64, 1273, 790, 179*percent, 12);
}

function myMonAttacks(){
	// animate attack and other pokemon getting hurt
	leftMon.attacking();
	setTimeout(function() {
		rightMon.hurting();
	}, 500);
	// update health and health sprite
	enemy.curHP = enemy.curHP - myMon.strength;
	updateRightHealth();
		if(enemy.curHP <= 0){
			return 1;
		}
		else{
		return 0;}
}

function enemyAttacks(){
	// animate attack and other pokemon getting hurt
	rightMon.attacking();
	setTimeout(function() {
		leftMon.hurting();
	}, 500);
	// update health and health sprite
	myMon.curHP = myMon.curHP - enemy.strength;
	updateLeftHealth();
	if(myMon.curHP <= 0){
		return 1;
	}
	else{
		return 0;
	}
}

function fight(){
	// POKEMON WITH GREATER SPEED GOES FIRST
	if(myMon.attackSpeed >= enemy.attackSpeed) {
		enemyFainted = myMonAttacks();
		setTimeout(function() {
			if(enemyFainted != 1){
				playerFainted = enemyAttacks();
				setTimeout(function() {
				if(playerFainted != 1){
					gameLoop();}
				else{
					playerFaints();}
			}, 2700);
			}
			else {
				enemyFaints();
			}
		}, 2700);
	}
	else {
		playerFainted = enemyAttacks();
		setTimeout(function() {
		if(playerFainted != 1){
			enemyFainted = myMonAttacks();
			setTimeout(function() {
			if(enemyFainted != 1){
				gameLoop();
			}
			else {
				enemyFaints();}
			}, 2700);
		}
		else {
			playerFaints();}
		}, 2700);
		// else call in next pokemon or end game
	}
}


function viewPokemon(){
	myArray = [];
	// add live pokemon to myArray
	i = 0;
	j = 0;
	while(i < playerTeam.numLeft){
		if(playerTeam.monArray[i].name != myMon.name){
			myArray[j] = playerTeam.monArray[i];
			j++;
		}
		i++;
	}
	
	// Clear buttons
	promptText.innerHTML="My Pokemons";
	button1.innerHTML = " ";
	button2.innerHTML = " ";
	button3.innerHTML = " ";
	button4.innerHTML = " ";
	button5.innerHTML = " ";
	button6.innerHTML = " ";
	
	if(myMon.curHP > 0) {
		button6.innerHTML = "Keep out " + myMon.name;
		button6.onclick = function(){gameLoop();}
	}
	
	if(myArray.length >= 1){
		button1.innerHTML = myArray[0].name;
		button1.onclick = function(){sureSwitch(myArray[0]);}
	}
	if(myArray.length >= 2){
		button3.innerHTML = myArray[1].name;
		button3.onclick = function(){sureSwitch(myArray[1]);}
	}
	if(myArray.length >= 3){
		button2.innerHTML = myArray[2].name;
		button2.onclick = function(){sureSwitch(myArray[2]);}
	}
	if(myArray.length >= 4){
		button4.innerHTML = myArray[3].name;
		button4.onclick = function(){sureSwitch(myArray[3]);}
	}
	if(myArray.length >= 5){
		button5.innerHTML = myArray[4].name;
		button5.onclick = function(){sureSwitch(myArray[4]);}
	}
	
	//CLICKING A BUTTON WOULD ASK USER IF THEYRE SURE THEY WANT TO SWITCH POKEMON
	
}

function viewBag(){
	
	// THIS WOULD SHOW THE USER"S ITEM TOTALS
	promptText.innerHTML="What's in my Bag?";
	button1.innerHTML = "Potions: " + potionCount +  + ")";
	button3.innerHTML = "Don't use item (Go back)";
	button2.innerHTML = "Pokeballs: " + /*numPokeballs + */ + ")";
	button4.innerHTML = " ";
	button5.innerHTML = " ";
	button6.innerHTML = " ";
	
	//CLICKING A BUTTON WOULD ASK USER IF THEYRE SURE THEY WANT TO USE ITEM
	button1.onclick = function(){sureUseItem("potion");}
	button2.onclick = function(){sureUseItem("pokeball");} 
	button3.onclick = function(){gameLoop();}
}

function run(){
	// Makes pokemon disappear and changes prompt and clears buttons
	context.clearRect(0,0,900,740);
	promptText.innerHTML="Got away safely";	
	button1.innerHTML = " ";
	button2.innerHTML = " ";
	button3.innerHTML = " ";
	button4.innerHTML = " ";
	button5.innerHTML = " ";
	button6.innerHTML = " ";
	// return to map/town 
	setTimeout(function(){
			window.location.replace("../play/worldMap.html");
	}, 2000);
}

function endGame(){
	// STILL HAVE TO CODE FINAL VERSION
	// this bases the winner off of whether or not player's one pokemon faints or not
	// final version will base winner off whether or not all player's pokemon faint
	if(playerTeam.numLeft == 0){
		promptText.innerHTML="All your Pokemon fainted...";	
	}
	else{ // User wins
		promptText.innerHTML = "You beat " + enemy.name + "!";
	}
	// Add a return to map/town option
	button1.innerHTML = "Leave Gym";
	button2.innerHTML = "";
	button3.innerHTML = " ";
	button4.innerHTML = " ";
	button5.innerHTML = " ";
	button6.innerHTML = " ";
	
	// turn buttons to go back to map
	button1.onclick = function(){ window.location.replace("../play/worldMap.html");}
	button2.onclick = function(){ window.location.replace("../play/worldMap.html");}
	button3.onclick = function(){ window.location.replace("../play/worldMap.html");}
	button4.onclick = function(){ window.location.replace("../play/worldMap.html");}
	button5.onclick = function(){ window.location.replace("../play/worldMap.html");}
	button6.onclick = function(){ window.location.replace("../play/worldMap.html");}
}

function sureSwitch(pokemon){
	// MAKES SURE USER WANTS TO SWITCH
	promptText.innerHTML = "Are you sure you want to switch Pokemon?";
	button1.innerHTML = "Yes";
	button2.innerHTML = "No";
	button3.innerHTML = " ";
	button4.innerHTML = " ";
	button5.innerHTML = " ";
	button6.innerHTML = " ";
	
	// EITHER GOING TO SWITCH TO POKEMON OR RETURN TO VIEW SCREEN
	button1.onclick = function(){
		i = 0;
		j = 0;
		while(i < playerTeam.numLeft){
			if(playerTeam.monArray[i].name == pokemon.name){
				j = i;
			}
			i++;
		}
		myMon = playerTeam.swapPokemon(j);
		leftMon = playerSprites[j];
		context.clearRect(400, clearY1, clearX2, clearY2);
		leftMon.startPos();
		context.clearRect(300, 740, 350, 770);
		context.fillText(myMon.name, 350, 770);
		updateLeftHealth();
		promptText.innerHTML= "Go " + myMon.name +"!";
		setTimeout(function() {
			enemyAttacks();
			if(myMon.curHP > 0 ){
				gameLoop();
			}
			else {
				endGame();
			}
			}, 1700);
		}
	button2.onclick = function(){viewPokemon();}
}

function sureUseItem(itemName){
	// MAKES SURE USER WANTS TO USE ITEM
	promptText.innerHTML = "Are you sure you want to use this item?";
	button1.innerHTML = "Yes";
	button2.innerHTML = "No";
	button3.innerHTML = " ";
	button4.innerHTML = " ";
	button5.innerHTML = " ";
	button6.innerHTML = " ";
	
	// EITHER GOING TO USE THE ITEM OR RETURN TO VIEW SCREEN
	button1.onclick = function(){
		if(itemName == "potion"){
			promptText.innerHTML = myMon.name + " regained health";
			myMon.usePotion();
			updateLeftHealth();
			setTimeout(function() {
				playerFainted = enemyAttacks();
				if(playerFainted != 1){
					gameLoop();
				}
				else {
					playerFaints();
				}
			}, 1700);
		}
		else { //pokeball
			catchMon();
		}
	}
	button2.onclick = function(){viewBag();}
}

function enemyFaints(){
	rightMon.fainting();
	promptText.innerHTML= enemy.name + " fainted";
	setTimeout(function() {
		if(enemyTeam.numLeft > 1){
			curEnemy += 1;
			enemy = enemyTeam.swapPokemon(curEnemy);
			curEnemySprite += 1;
			rightMon = enemySprites[curEnemySprite];
			rightMon.startPos();
			context.clearRect(1100, 740, 1185, 770);
			context.fillText(enemy.name, 1185, 770);
			updateRightHealth();
			enemyTeam.numLeft -= 1;
			promptText.innerHTML= enemy.name + " appears";
			gameLoop();
		}
		else {
			endGame();
		}
	}, 2000);

}
 function playerFaints(){
	leftMon.fainting();
	promptText.innerHTML= myMon.name + " fainted";
	button1.innerHTML = "";
	button2.innerHTML = "";
	button3.innerHTML = " ";
	button4.innerHTML = " ";
	button5.innerHTML = " ";
	button6.innerHTML = " ";
	playerTeam.numLeft -= 1;
	context.clearRect(400, clearY1, clearX2, clearY2);
	myArray = [];
	// add live pokemon to myArray
	i = 0;
	while(i < playerTeam.monArray.length){
		if(playerTeam.monArray[i].name == myMon.name){
			playerTeam.monArray.splice(i, 1);
			playerSprites.splice(i,1);
			
		}
		i++;
	}
	context.clearRect(400, clearY1, clearX2, clearY2);
	setTimeout(function() {
		if(playerTeam.numLeft > 0) {
			viewPokemon();
		}
		else {
			endGame();
		}
	}, 2000);
 }
 
 function catchMon(){
	 // catch the enemy pokemon
	promptText.innerHTML=" ";	
	button1.innerHTML = " ";
	button2.innerHTML = " ";
	button3.innerHTML = " ";
	button4.innerHTML = " ";
	button5.innerHTML = " ";
	button6.innerHTML = " ";
	context.drawImage(ballImg, 0, 0, 767, 767, 1300, 600, 20, 20);
	setTimeout(function() {
		context.clearRect(1100, clearY1, clearX2, clearY2);
		context.drawImage(ballImg, 0, 0, 767, 767, 1300, 600, 20, 20);
		pokeCatch.play()
	}, 500);
	promptText.innerHTML= enemy.name + " caught! Added to Pokedex.";
	// ADD TO POKEDEX
	
	// return to map/town 
	setTimeout(function(){
			window.location.replace("../play/worldMap.html");
	}, 4000);
 }