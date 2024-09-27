using Concesionaria.ModelViews;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Essentials;
using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace Concesionaria.Views
{
	[XamlCompilation(XamlCompilationOptions.Compile)]
	public partial class ClienteRegistro : ContentPage
	{
		public ClienteRegistro ()
		{
			InitializeComponent ();
        }

		public async void subirImagen(object sender, EventArgs e )
		{
			var options = new PickOptions()
			{
				PickerTitle = "Selecciona Archivo",
				FileTypes = FilePickerFileType.Images
			};
			
			var contenido = await FilePicker.PickAsync(options);

			ClienteRegistroViewModel.subidaImagen = contenido;
		}

    }
}