<?php namespace Controllers;

use models\cine as Cine;
//use Controllers\CineController as CineController;
use Repository\CinesRepository as CinesRepository;

?>
<!-- SWEET ALERT -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- SWEET ALERT -->
<?php
/**
 * 
 */
class CineController 
{

//----------------ATRIBUTOS-----------------------
	 private $DAOCines;
//----------------CONSTRUCTOR---------------------
	function __construct()
	{
		$this->DAOCines=\DAO\CinesDAO::getInstance();
       
	}

//----------------METODOS--------------------------
	public function index()
        {
            
            if(isset($_SESSION['Login']))//Si hay session:
            {
                

                if($_SESSION['Login']->getRol()==1)//SI ES ADMIN LO LLEVA A SU PAG (falta configurar esto)
                {
                    //lo lleva al home ADM
                    $arrayCines=$this->DAOCines->traerTodos();//levanto todos los cines de la BD antes de el llamado a la vista
                    require(ROOT . '/Views/Adm/home_adm.php');//
                    
                }
                if($_SESSION['Login']->getRol()==2)// SI ES CLIENTE AL HOME DE CLIENTE (falta configurar esto)
                {
                    
                    //lo lleva al home CLIENTE
                    
                    require(ROOT . '/Views/User/home_usuario.php');
                    
                }
            }

            else
            {
                
                require(ROOT . '/Views/home.php');//SI NO HAY SESSION LO LLEVA A HOME (como no hay ninguna session lo lleva al home.php como anonimo)
            }
        }//fin index-------
	//
	//
	public function newCine($cine,$direccion,$valor,$capacidad){
        

        
        $newCine = new cine ($cine,$direccion,$capacidad,$valor,true);//creo el nuevo cine

        if ($newCine->getValor_entrada() == null || $newCine->getCapacidad() == null)
        {
            echo '<script language="javascript">alert("El campo debe tener valores positivos!");</script>';
            $this->index();
        }
        else if($this->DAOCines->buscarPorID($newCine->getID() )!=null ) // Verifica que no exista otro Cine con el mismo id en BD
        {
            
            echo '<script language="javascript">alert("El ID del cine ya se encuentra registrado!");</script>';
            $this->index();
        }
        else if(strlen($cine)>30 ) // Verifica que el nombre del cine no supere los 30 caracteres maximos usados en BD
        {
            
            echo '<script language="javascript">alert("El Nombre del cine exede los 30 caracteres!");</script>';
            $this->index();
        }
        else if(strlen($direccion)>30 ) // Verifica que la direccion del cine  no supere los 30 caracteres maximos usados en BD
        {
            
            echo '<script language="javascript">alert("La direccion  del cine exede los 30 caracteres!");</script>';
            $this->index();
        }
        else if($this->DAOCines->buscarPorNombre($newCine->getNombre() )!=null ) // Verifica que no exista otro Cine con el mismo nombre en BD
        {
            
            echo '<script language="javascript">alert("El Nombre del cine ya se encuentra registrado!");</script>';
            $this->index();
        }
        else
        {
        $this->DAOCines->insertar($newCine);
          //este tipo de mensaje no rompe el codigo
        $this->index(); //llamo al index de esta clase para redirigirlo a la vista que  sea correspondiente
        ?><script> sweetAlert("Añadir", "Cine añadido correctamente!", "success")</script>
            <?php
        }
    }//fin newcine
    //
    //
	public function deleteCine($id_cine){
        $this->DAOCines->borrar($id_cine);         
        $this->index();
        ?><script> sweetAlert("Eliminar", "Cine eliminado correctamente!", "success")</script>
        <?php
	}//fin delete cine
	//
	//
	public function modifyCine($id,$cine,$habilitado,$direccion,$capacidad,$valor){
        


        if (strlen($cine)>30){//verifico tamaño del nombre
           echo '<script language="javascript">alert("El nombre del cine exede los 30 caracteres!");</script>';
           $this->index();
        }
        else if(strlen($direccion)>30 ) // Verifica que la direccion del cine  no supere los 30 caracteres maximos usados en BD
        {
            
            echo '<script language="javascript">alert("La direccion  del cine exede los 30 caracteres!");</script>';
            $this->index();
        }
        else{//si esta todo bien , modifico el cine

            if ($habilitado==1)
            $cineMod = new cine ($cine,$direccion,$capacidad,$valor,1);
        else if($habilitado==0)
            $cineMod = new cine ($cine,$direccion,$capacidad,$valor,0);

        $cineMod->setID($id);
        $this->DAOCines->actualizar($cineMod);
        
        echo '<script language="javascript">alert("Cine modificado satisfactoriamente!");</script>'; //este tipo de mensaje no rompe el codigo
        $this->index(); //llamo al index de esta clase para redirigirlo a la vista que  sea correspondiente

        }//fin else
        


    }//fin modify cine
    //
    //
    public function habilitado(){

    }//fin habilitar
    //
    //
    public function traerTodos(){
        
        $arrayCines= array();
        $arrayCines=$this->DAOCines->traerTodos();

        if($arrayCines!=null){

            return $arrayCines;
        }
        else{
            
            echo '<script language="javascript">alert("No hay Cines cargados en BD!");</script>';
            return null;
        }
    }//traer todos
    //
    //
    //

}//fin class

?>