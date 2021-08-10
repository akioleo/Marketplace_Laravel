<?php
namespace App\Traits;

trait UploadTrait
{
    //Se $imageColumn vier nulo, sabemos que se trata de uma logo, se não vier nulo, products
    private function imageUpload($images, $imageColumn = null)
    {
        $uploadedImages = [];

        //Verificar se é array (quando é multiple igual products, virá array com vários objetos)
        //Como logo é só um arquivo, já vem upLoadedFile (que não é array), caindo no else
        if(is_array($images))
        {
            //Vai criar dentro da pasta storage->app->public uma pasta products
            //Irá criar uma string com o novo nome da imagem que será utilizado para chamar no site
            foreach($images as $image){
                //Cada vez que fizer o upload, terá o nome da imagem com a pasta
                //Passa o imagColumn e o valor dele vai ser o valor da imagem
                $uploadedImages[] = [$imageColumn =>$image->store('products', 'public')];
            }
        } else {
                //Se ele vier nulo, adiciona a $images e já faz store na pasta logo, com disk public
                $uploadedImages = $images->store('logo', 'public');
            }
        //retorna o array com o nome das imagens que fez o upload
        return $uploadedImages;
    }
}