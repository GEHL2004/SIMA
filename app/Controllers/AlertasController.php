<?php

namespace App\Controllers;

class AlertasController
{

    public static function success(string $titulo)
    {
        $_SESSION['mensaje'] = '
                <script>
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "' . $titulo . '",
                        showConfirmButton: false,
                        timer: 3000
                    });
                </script>
            ';
    }

    public static function info(string $titulo, string $texto)
    {
        $_SESSION['mensaje'] = '
                <script>
                    Swal.fire({
                        icon: "info",
                        title: "' . $titulo . '",
                        text: "' . $texto . '",
                        confirmButtonColor: "#3FC3EE"
                    });
                </script>
            ';
    }

    public static function warning(string $titulo, string $texto)
    {
        $_SESSION['mensaje'] = '
                <script>
                    Swal.fire({
                        icon: "warning",
                        title: "' . $titulo . '",
                        text: "' . $texto . '",
                        confirmButtonColor: "#F8BB86"
                    });
                </script>
            ';
    }

    public static function error(string $titulo, string $texto)
    {
        $_SESSION['mensaje'] = '
                <script>
                    Swal.fire({
                        icon: "error",
                        title: "' . $titulo . '",
                        text: "' . $texto . '",
                        confirmButtonColor: "#F27474"
                    });
                </script>
            ';
    }
}
