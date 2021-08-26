/*
class plyr{
	//Initializaiton
	username;
	id;
	wallet = {
		money: 100, 
		candy: 2,
		heal_potion: 0
	}
	pokedex = [];

	 inventory = {
		 items: {
			money: 100; 
			candy: 2,
			heal_potion: 0
	  },
		pokemon: [] };

	

	//Constructor
	constructor(username,id,wallet,pokedex){
		this.username = username;
		this.id = id;
		this.wallet = wallet;
		this.pokedex = pokedex;
	};

	walletJSON(){
		return JSON.stringify(this.wallet);
	};

	pokedexJSON(){
		return JSON.stringify(this.pokedex);
	};
};
*/

class wallet{
	money = 100;
	pokeballs = 3;
	candy = 2;
	heal_potion => 1;

	constructor(walletJSON){
		let wllt = JSON.parse(walletJSON);
		let items = Object.getOwnPropertyNames(wllt);
		for(let i = 0; i < items.length; i++){
			this[items[i]] = wllt[items[i]];
		}
	}

	getWalletJSON(){
	  return JSON.stringify(this);
	}

	addMoney(amount){
		this.money += amount;
	}

	removeMoney(amount){
		this.money -= amount;
	}

	addPokeballs(amount){
		this.pokeballs += amount;
	}

	usePokeball(){
		this.pokeballs -= 1;
	}

	addCandy(amount){
		this.candy += amount;
	}

	useCandy(){
		this.candy -= 1;
	}

	addHealPotion(amount){
		this.heal_potion += amount;
	}

	useHealPotion(){
		this.heal_potion -= 1;
	}


}

class pkmn{
	id = null;
	name = null;
	type1 = null;
	type2 = null;
	total = -1;
	max_hp = -1;
	attack = -1;
	defense = -1;
	special_attack = -1;
	special_defense = -1;
	speed = -1;
	generation = -1;
	legendary = false;
	unique_id = null;
	hp =  -1;
	experience = 0;
	level = 1;
	exhausted = false;
	canEvolve = true;
	

	constructor(pokemonJSON){
		let pokemon = JSON.parse(pokemonJSON);
		let specs = Object.getOwnPropertyNames(pokemon);
	  	for(let i = 0; i < specs.length; i++){
	  	  this[specs[i]] = pokemon[specs[i]];
	  	}
	};

	getPokemonJSON(){
	  return JSON.stringify(this);
	}

	damage(amount){
		this.hp -= amount;
	}

	heal(amount){
		this.hp += amount;
	}

	gainExperience(amount){

	}

	train(){

	}

	evolve(){

	}
}