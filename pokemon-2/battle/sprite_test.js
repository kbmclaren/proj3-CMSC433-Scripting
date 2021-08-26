// YouTube video I based these movements off of https://www.youtube.com/watch?v=MgNm2WZtGWY

// This is movements for the enemy pokemon, looking for sounds and background music to add

// Get pokemon image
let enemy = new Image();
enemy.src = '004Charmander.png';

let myMon = new Image();
myMon.src = '007squirtle_reversed.png';

// declare animation Interval
let animationInterval;


// get canvas context
var canvas = document.getElementById("myCanvas");
var context = canvas.getContext('2d');

// upload background image
let background = new Image();
background.src = 'background2.png';
context.drawImage(background, 0, 0);

// variables
// enemy variables
const eWidth = 600;
const eHeight = 600;
const ratio = 3;
const adjeWidth = eWidth/ratio;
const adjeHeight = eHeight/ratio;
const eStartX = 1200;
const eStartY = 450;
var eCurX = eStartX;
var eCurY = eStartY;
var speed = 100;
const bgeHeight = 900;
const bgeWidth = 1800;

// player variables
const origWidth = 600;
const origHeight = 600;
const myWidth = origWidth/ratio;
const myHeight = origHeight/ratio;
const myStartX = 500;
const myStartY = 450;
var myCurX = myStartX;
var myCurY = myStartY;

// Audio
var bgMusic = new Audio('battle_music.mp3');
var scratch = new Audio('scratch.wav');
var cut = new Audio('cut.wav');
var faint = new Audio('faint.mp3');

// calls init to start animation
myMon.onload = function() {
	init();
}

function stopSprite(){
		clearInterval(animationInterval);
}

function init(){
	context.drawImage(enemy, 0, 0, eWidth, eHeight, eCurX, eCurY, adjeWidth, adjeHeight);
	context.drawImage(myMon, 0, 0, origWidth, origHeight, myCurX, myCurY, myWidth, myHeight);
}

// Movement for a basic attack
function enemyAttacking(){
	scratch.play();
	lastmove = 0;
	speed = 100;
	stopSprite();
	animationInterval = setInterval(() => {
		context.clearRect(0,0, canvas.eWidth, canvas.eHeight);
		context.drawImage(background, 0, 0);
		context.drawImage(myMon, 0, 0, origWidth, origHeight, myStartX, myStartY, myWidth, myHeight);
		if(lastmove == 0){
			eCurX = eCurX-30;
			eCurY = eCurY-5;
			context.drawImage(enemy, 0, 0, eWidth, eHeight, eCurX, eCurY, adjeWidth, adjeHeight);
			lastmove = 1;
		}
		else if(lastmove==1){
			eCurX = eCurX-30;
			eCurY = eCurY+5;
			context.drawImage(enemy, 0, 0, eWidth, eHeight, eCurX, eCurY, adjeWidth, adjeHeight);
			lastmove = 2;
		}
		
		else if(lastmove == 2 || lastmove == 3){
			eCurX = eCurX+30;
			context.drawImage(enemy, 0, 0, eWidth, eHeight, eCurX, eCurY, adjeWidth, adjeHeight);
			lastmove = lastmove + 1;
		}
		else {
			stopSprite();
			context.drawImage(enemy, 0, 0, eWidth, eHeight, eStartX, eStartY, adjeWidth, adjeHeight);
		}
	}, speed);
	setTimeout(function() {
		myMonHurt();
	}, 500);
}	

function myMonHurt(){
	lastmove = 0;
	speed = 200;
	stopSprite();
	animationInterval = setInterval(() => {
		context.clearRect(0,0, canvas.eWidth, canvas.eHeight);
		context.drawImage(background, 0, 0);
		context.drawImage(enemy, 0, 0, eWidth, eHeight, eStartX, eStartY, adjeWidth, adjeHeight);
		if(lastmove < 6 && lastmove % 2 == 0 ){
			context.drawImage(myMon, 0, 0, origWidth, origHeight, myCurX, myCurY, myWidth, myHeight);
			lastmove = lastmove + 1;
		}
		else if (lastmove < 6) {
			lastmove = lastmove + 1;
		}
		else {
			stopSprite();
			context.drawImage(myMon, 0, 0, origWidth, origHeight, myStartX, myStartY, myWidth, myHeight);
		}
	}, speed);
}

// movement for when pokemon is hit by an attack
function myMonAttacking(){
	cut.play();
	lastmove = 0;
	speed = 100;
	stopSprite();
	animationInterval = setInterval(() => {
		context.clearRect(0,0, canvas.eWidth, canvas.eHeight);
		context.drawImage(background, 0, 0);
		context.drawImage(enemy, 0, 0, eWidth, eHeight, eStartX, eStartY, adjeWidth, adjeHeight);
		if(lastmove == 0){
			myCurX = myCurX+30;
			myCurY = myCurY-5;
			context.drawImage(myMon, 0, 0, origWidth, origHeight, myCurX, myCurY, myWidth, myHeight);
			lastmove = 1;
		}
		else if(lastmove==1){
			myCurX = myCurX+30;
			myCurY = myCurY+5;
			context.drawImage(myMon, 0, 0, origWidth, origHeight, myCurX, myCurY, myWidth, myHeight);
			lastmove = 2;
		}
		else if(lastmove == 2 || lastmove == 3){
			myCurX = myCurX-30;
			context.drawImage(myMon, 0, 0, origWidth, origHeight, myCurX, myCurY, myWidth, myHeight);
			lastmove = lastmove + 1;
		}
		else {
			stopSprite();
			context.drawImage(myMon, 0, 0, origWidth, origHeight, myStartX, myStartY, myWidth, myHeight);
		}
	}, speed);
	setTimeout(function() {
		enemyHurt();
	}, 500);
}

function enemyHurt(){
	lastmove = 0;
	speed = 200;
	stopSprite();
	animationInterval = setInterval(() => {
	context.clearRect(0,0, canvas.eWidth, canvas.eHeight);
	context.drawImage(background, 0, 0);
	context.drawImage(myMon, 0, 0, origWidth, origHeight, myCurX, myCurY, myWidth, myHeight);
	if(lastmove < 6 && lastmove % 2 == 0 ){
		context.drawImage(enemy, 0, 0, eWidth, eHeight, eCurX, eCurY, adjeWidth, adjeHeight);
		lastmove = lastmove + 1;
	}
	else if (lastmove < 6) {
		lastmove = lastmove + 1;
	}
	else {
		stopSprite();
		context.drawImage(enemy, 0, 0, eWidth, eHeight, eStartX, eStartY, adjeWidth, adjeHeight);
	}
	}, speed);
}

// movement for when pokemon faints (loses all health)
// Once this function is called, reload the page to reset the pokemon
function fainting(){
	faint.play();
	stopSprite();
	speed = 100;
	context.drawImage(myMon, 0, 0, origWidth, origHeight, myCurX, myCurY, myWidth, myHeight);
	animationInterval = setInterval(() => {
			context.clearRect(0,0, canvas.eWidth, canvas.eHeight);
			context.drawImage(background, 10, 10);
			context.drawImage(myMon, 0, 0, origWidth, origHeight, myStartX, myStartY, myWidth, myHeight);
			if( eCurY < 900-adjeHeight){ //about the eHeight of the background
				eCurY = eCurY + 10;
				context.drawImage(enemy, 0, 0, eWidth, eHeight, eCurX, eCurY, adjeWidth, adjeHeight);
			}
			else {
				stopSprite();
			}
	}, speed);	
}

// Click to play background music. (Chrome does not allow audio to play unless the user interacts with the page)
document.addEventListener('click', function(event){
	bgMusic.play();
})

// Event listners for testing purposes
document.addEventListener('keydown',function(event){
	if(event.code == "ArrowRight"){
		myMonAttacking();
	}
})

document.addEventListener('keydown',function(event){
	if(event.code == "ArrowLeft"){
		enemyAttacking();
	}
})

document.addEventListener('keydown',function(event){
	if(event.code == "ArrowDown"){
		fainting();
	}
})

