<?php

	class AmountOfAttention
	{
		public $id;
		public $name;
		public $description;
	}
	
	class Difficulty
	{
		public $id;
		public $name;
		public $description;
	}
	
	class DishType
	{
		public $id;
		public $name;
		public $description;
	}
	
	class Ingredient
	{
		public $id;
		
		public $amount; 	//Used only if included in a recipe
		public $unitName;	//Used only if included in a recipe

		public $name;
		public $ingredientUnit;
		public $ingredientType;
		public $ingredientStorage;
	}
	
	class IngredientInStore
	{
		public $id;
		public $ingredient;
		public $amount;
	}
	
	class IngredientStorage
	{
		public $id;
		public $name;
		public $description;
	}
	
	class IngredientType
	{
		public $id;
		public $name;
		public $description;
	}
	
	class IngredientUnit
	{
		public $id;
		public $name;
		public $shorthand;
	}
	
	class ManufacturingTime
	{
		public $id;
		public $name;
		public $minimumTime;
		public $maximumTime;
	}
	
	class Rating
	{
		public $id;
		public $description;
		public $stars;
	}
	
	class Recipe
	{
		public $id;
		public $name;
		public $description;
		public $manufacturingTime;
		public $resultType;
		public $amountOfAttention;
		public $difficulty;
		public $dishType;
		public $ingredients;
	}
	
	class RecipeHasIngredient
	{
		public $id;
		public $amount;
		public $recipe;
		public $ingredient;
	}
	
	class RecipeHistory
	{
		public $id;
		public $recipe;
		public $date;
		public $rating;
		public $personaleComment;
	}
	
	class ResultType
	{
		public $id;
		public $name;
		public $description;
	}
	
	
