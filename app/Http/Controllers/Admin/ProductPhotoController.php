<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ProductPhoto;
use Illuminate\Http\Request;
//Adicionar o namespace para Storage::
use Illuminate\Support\Facades\Storage;

class ProductPhotoController extends Controller
{
    //Buscar a foto pelo ID dela, buscar do banco e pegaria o campo 'image' que teria o nome da imagem e faria o delete abaixo (OPCIONAL)
    //Neste exemplo estamos buscando diretamente pelo nome
    //Remover dos arquivos
    public function removePhoto(Request $request)
    {
        //Está pegando o campo 'photoName' passado pelo input hidden na view  
        $photoName = $request->get('photoName');

        //verificar se o arquivo existe na pasta (App->storage->public)
        //o objeto Storage:: gerenciar toda parte de armazenamento a nivel de arquivos 
        //Verificar se determinado arquivo existe em determinado diretório (discos que está em filesystems.php, 'local', 'public', 's3')
        if(Storage::disk('public')->exists($photoName))
        {
            //a imagem existindo, remove o arquivo
            Storage::disk('public')->delete($photoName);
        }
        //Remover a imagem do database
        //Condicional onde image seja igual a $photoName que esta recebendo da url
        $removePhoto = ProductPhoto::where('image', $photoName);
        //pega o primeiro resultado exatamente(first) e desse resultado pega o valor do id dele
        $productId = $removePhoto->first()->product_id;

        $removePhoto->delete();

        flash('Imagem removida com sucesso!') -> success();
        //Está pegando o productId com base no nome da imagem na busca do "Product::photo where..."
        return redirect()-> route('admin.products.edit', ['product' => $productId]);

    }
}
