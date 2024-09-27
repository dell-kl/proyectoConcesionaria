using Concesionaria.Models;
using Concesionaria.Resource;
using Concesionaria.Views.Alertas;
using Newtonsoft.Json;
using Rg.Plugins.Popup.Services;
using System;
using System.Collections.Generic;
using System.Net.Http;
using System.Net;
using System.Text;
using System.Windows.Input;
using Xamarin.Forms;
using System.Linq;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using Concesionaria.Views;

namespace Concesionaria.ModelViews
{
    public class VerificarAsignacionViewModel : INotifyPropertyChanged
    {
        public ICommand buscar {  set; get; }
        public CompraDetalle compraDetalle { set; get; }

        public List<Resumen> resumen { set; get; } = new List<Resumen>();

        public List<Resumen> Resumen { get {  return resumen; } set {
                resumen = value;
                PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(nameof(Resumen)));
            } }

        public string datoBuscar { set; get; }

        public VerificarAsignacionViewModel()
        {
            buscar = new Command(buscarEvent);
        }

        public event PropertyChangedEventHandler PropertyChanged;

        public async void buscarEvent()
        {

            using (HttpClient solicitud = new HttpClient()) // -> usamos y cerramos conexion.
            {
                var entrada = await solicitud.GetAsync($"http://{IPv4.ip}/comprasDetalle/cedulaCliente/{this.datoBuscar}");


                if (entrada.StatusCode.Equals(HttpStatusCode.OK))
                {
                    var datos = await entrada.Content.ReadAsStringAsync();
                    this.resumen = JsonConvert.DeserializeObject<List<Resumen>>(datos);
                    //dentro de este punto vamos a mostrar los datos

                    if ( resumen.Count == 0 )
                    {
                        await PopupNavigation.Instance.PushAsync(new AlertaMensaje("Sin autos asignados", "cancelar.png", "volver al buscador"));
                    }
                    else
                    {
                        await App.Current.MainPage.Navigation.PushAsync(new VerificarAsignacion());
                    }
                }
                else
                {
                    await PopupNavigation.Instance.PushAsync(new AlertaMensaje("Error de busqueda", "cancelar.png", "volver al buscador"));
                }
            }
        }

    }
}
