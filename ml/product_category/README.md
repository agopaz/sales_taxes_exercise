A simple product category predictor
===================================

The purpose of this machine learning model is to predict a category based on product name.
I use a simple training dataset `./data/dataset.csv` in which there are some product names and categories.<br />
The  training dataset could be enlarged in order to increase the number of categories and products guessed.<br />
To simple train and save the model, from the project root execute the command:

`./src/console.php productCategory:generateModel`

This generate a `./data/model.rbx` that can be used to guess category from product name, with the command:

`./src/console.php productCategory:guess <productName>`
