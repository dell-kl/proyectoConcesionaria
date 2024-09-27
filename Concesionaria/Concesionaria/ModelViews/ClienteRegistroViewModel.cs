using Concesionaria.Models;
using Concesionaria.Resource;
using Concesionaria.Views.Alertas;
using Newtonsoft.Json;
using Rg.Plugins.Popup.Services;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.IO;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Input;
using Xamarin.Essentials;
using Xamarin.Forms;

namespace Concesionaria.ModelViews
{
    class ClienteRegistroViewModel 
    {
        public ICommand BotonRegistrarCliente { set; get; }
        public ICommand Retornar { set; get; }
        public Cliente cliente { set; get; } = new Cliente();

        public static FileResult subidaImagen { set; get; }

        public ClienteRegistroViewModel()
        {
            BotonRegistrarCliente = new Command(GestionarRegistroCliente);
            this.Retornar = new Command(retornarHome);
        }


        public async void GestionarRegistroCliente()
        {
            //debemos parsear algunas cosas en mi data para poder enviar...
            this.cliente.cliente_genero = this.cliente.cliente_genero.Equals("Masculino") ? "1" : "0";

            var data = JsonConvert.SerializeObject(this.cliente);
            var respuesta = new StringContent(data, Encoding.UTF8, "application/json");

            using (HttpClient solicitud = new HttpClient()) // -> usamos y cerramos conexion.
            {
                try
                {
                    var entrada = await solicitud.PostAsync($"http://{IPv4.ip}/clientes/registrar", respuesta);

                    this.cliente = new Cliente();

                    var contenido = await entrada.Content.ReadAsStringAsync();

                    if (entrada.StatusCode.Equals(HttpStatusCode.OK))
                    {
                        await PopupNavigation.Instance.PushAsync(new AlertaMensaje("Cliente registrado exitosamente", "check.png"));
                    }
                    else
                    {
                        await PopupNavigation.Instance.PushAsync(new AlertaMensaje("No se logro registrar el cliente", "cancelar.png"));
                    }

                }
                catch( Exception e )
                {
                    await PopupNavigation.Instance.PushAsync(new AlertaMensaje("Error de conexion", "cancelar.png"));
                }

            }

        }

        public void retornarHome()
        {
            App.Current.MainPage.Navigation.PopAsync();
        }


        public async Task<string> ConvertImageToBase64Async(FileResult imageFile)
        {
            if (imageFile == null)
                return null;

            using (var stream = await imageFile.OpenReadAsync())
            using (var memoryStream = new MemoryStream())
            {
                await stream.CopyToAsync(memoryStream);
                var imageBytes = memoryStream.ToArray();
                return Convert.ToBase64String(imageBytes);
            }
        }
    }
}
