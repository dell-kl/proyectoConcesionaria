<?php

    class DbConexion 
    {
        private string $host = "MACHINE_DELL\DELLKL";
        private string $db = "concesionariaDB";
        private string $user = "sa";
        private string $pass = "sa12345";

        

        public function conexion_baseDatos()
        {
            $conexion = "sqlsrv:Server=$this->host;Database=$this->db";
            $dbConexion = new PDO($conexion, $this->user, $this->pass);
            return $dbConexion;
        }
    }
?>