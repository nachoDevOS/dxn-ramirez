<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta name="description" content="{{ setting('site.description') }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <title>{{ setting('site.title') }} | Veterinaria y Urgencias en Trinidad</title>


    <?php 
        $admin_favicon = Voyager::setting('site.logo', ''); 
    ?>
    <link rel="shortcut icon" href="{{ $admin_favicon == '' ? asset('images/icon.png') : Voyager::image($admin_favicon) }}" type="image/png">


    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ setting('site.title') }} - Cuidado Profesional para tu Mascota">
    <meta property="og:description" content="{{ setting('site.description') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $admin_favicon == '' ? asset('images/icon.png') : Voyager::image($admin_favicon) }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ setting('site.title') }}">
    <meta name="twitter:description" content="{{ setting('site.description') }}">

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "VeterinaryCare",
            "name": "Consultorio Veterinario Cortez",
            "image": "{{ Voyager::image($admin_favicon) }}",
            "@id": "{{ url('/') }}",
            "url": "{{ url('/') }}",
            "telephone": "+51 [Tu Teléfono]",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "[Tu Dirección]",
                "addressLocality": "[Tu Ciudad]",
                "addressCountry": "[Tu País]"
            },
            "openingHoursSpecification": {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": [
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday"
                ],
                "opens": "08:00",
                "closes": "20:00"
            }
        }
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css"/>

    <style>
        /* Mueve la insignia de reCAPTCHA hacia arriba para que no se superponga con el botón de WhatsApp */
        .grecaptcha-badge {
            bottom: 100px !important;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    {{-- <script src="https://www.google.com/recaptcha/api.js"></script> --}}
    <script src="https://www.google.com/recaptcha/api.js?render={{ setting('system.reCaptchaKeySite') }}"></script>
    <script>
        $(document).ready(function() {
            $('#btn-submit').on('click', function(e){
                e.preventDefault();
                grecaptcha.ready(function() {
                    grecaptcha.execute("{{ setting('system.reCaptchaKeySite') }}", {
                        action: 'submit'
                    }).then(function(token) {
                        // Add your logic to submit to your backend server here.
                        $('.btn-submit').html('Enviando... <i class="fa fa-spinner fa-spin"></i>');
                        $('.btn-submit').attr('disabled', true);

                        $('#appointment-form').prepend('<input type="hidden" name="g_recaptcha_response" value="' + token + '">');
                        $('#appointment-form').prepend('<input type="hidden" name="action" value="validar_appointment_form">');
                        // Deshabilitar el botón y mostrar un mensaje de envío
                        $(this).prop('disabled', true).html('Enviando... <i class="fas fa-spinner fa-spin"></i>');
                        // Enviar el formulario


                        $('#appointment-form')[0].submit();
                    });
                });
            });
        });

    </script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                 @if($admin_favicon != '')
                    <img src="{{ Voyager::image($admin_favicon) }}" alt="{{setting('site.title')}}" style="height: 80px; position: absolute; top: 0; margin-right: 100px;">
                 @else
                     <i class="fas fa-paw me-2"></i>
                 @endif
                <span class="d-none d-lg-inline" style="margin-left: 170px;">{{ setting('site.title') }}</span><br>
                {{-- <span class="d-inline d-lg-none" style="margin-left: 180px;">{{\Illuminate\Support\Str::limit(setting('site.title'), 18, '')}}</span> --}}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#servicios">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#nosotros">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonios">Testimonios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-user me-1"></i> Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="hero">
        <div class="container">
            <h1>Cuidamos a tu mascota como si fuera nuestra</h1>
            <p class="lead">En {{setting('site.title')}} ofrecemos servicios médicos de calidad para perros, gatos y diferentes especies de mascotas. Tu mascota está en las mejores manos.</p>
            <a href="#cita" class="btn btn-success btn-lg me-3">Solicitar Cita</a>
            <a href="#servicios" class="btn btn-outline-light btn-lg">Nuestros Servicios</a>
        </div>
    </section>

    <!-- Servicios Section -->
    <section id="servicios" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title">Nuestros Servicios</h2>
            <div class="row">
                @foreach ($services as $item)
                    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3 + 1) * 100 }}">
                        <div class="card service-card h-100">
                            <div class="card-body text-center p-4">
                                <div class="service-icon">
                                    {!! $item->icon !!}
                                </div>
                                <h4>{{ $item->name }}</h4>
                                <p>{{ $item->observation }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Formulario de Citas -->
    <section id="cita" class="py-5">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Solicita una Cita</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="form-container">
                        <form id="appointment-form" class="form-edit-add1" action="{{ route('appointment.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nombre completo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        @php
                                            $countryCodes = ['591' => 'bo', '54' => 'ar', '55' => 'br', '56' => 'cl', '51' => 'pe', '1' => 'us', '34' => 'es'];
                                            $currentCode = old('country_code', '591');
                                            $currentFlag = $countryCodes[$currentCode] ?? 'bo';
                                        @endphp
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="min-width: 110px; text-align: left;">
                                            <span id="flag-icon" class="fi fi-{{ $currentFlag }}"></span> <span id="phone-code">+{{ $currentCode }}</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="setCountry('591', 'bo'); return false;"><span class="fi fi-bo"></span> Bolivia (+591)</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="setCountry('54', 'ar'); return false;"><span class="fi fi-ar"></span> Argentina (+54)</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="setCountry('55', 'br'); return false;"><span class="fi fi-br"></span> Brasil (+55)</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="setCountry('56', 'cl'); return false;"><span class="fi fi-cl"></span> Chile (+56)</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="setCountry('51', 'pe'); return false;"><span class="fi fi-pe"></span> Perú (+51)</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="setCountry('1', 'us'); return false;"><span class="fi fi-us"></span> USA (+1)</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="setCountry('34', 'es'); return false;"><span class="fi fi-es"></span> España (+34)</a></li>
                                        </ul>
                                        <input type="hidden" name="country_code" id="country_code" value="{{ $currentCode }}">
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="67285914" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    @error('country_code')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="pet-name" class="form-label">Nombre de la mascota <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('pet_name') is-invalid @enderror" id="pet-name" name="pet_name" value="{{ old('pet_name') }}" required>
                                    @error('pet_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="pet-type" class="form-label">Especie <span class="text-danger">*</span></label>
                                    <select class="form-select @error('pet_type') is-invalid @enderror" id="pet-type" name="pet_type" required>
                                        <option value="" selected disabled>Seleccione...</option>
                                        @foreach ($animals as $animal)
                                            <option value="{{$animal->id}}" {{ old('pet_type') == $animal->id ? 'selected' : '' }}>{{$animal->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('pet_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="pet_race" class="form-label">Raza <span class="text-danger">*</span></label>
                                    <select class="form-select @error('pet_race') is-invalid @enderror" id="pet_race" name="pet_race" required disabled>
                                        <option value="" selected disabled>Primero seleccione una especie</option>
                                    </select>
                                    @error('pet_race')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="pet-gender" class="form-label">Sexo de la mascota <span class="text-danger">*</span></label>
                                    <select class="form-select @error('pet_gender') is-invalid @enderror" id="pet-gender" name="pet_gender" required>
                                        <option value="" selected disabled>Seleccione...</option>
                                        <option value="Macho" {{ old('pet_gender') == 'Macho' ? 'selected' : '' }}>Macho</option>
                                        <option value="Hembra" {{ old('pet_gender') == 'Hembra' ? 'selected' : '' }}>Hembra</option>
                                        {{-- <option value="Desconocido">Desconocido</option> --}}
                                    </select>
                                    @error('pet_gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="pet_age" class="form-label">Edad de la mascota <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('pet_age') is-invalid @enderror" id="pet_age" name="pet_age" value="{{ old('pet_age') }}" required>
                                    @error('pet_age')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="appointment-date" class="form-label">Fecha de Atención <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" id="appointment-date" name="appointment_date" value="{{ old('appointment_date') }}" required>
                                    @error('appointment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="appointment-time" class="form-label">Hora de Atención <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('appointment_time') is-invalid @enderror" id="appointment-time" name="appointment_time" value="{{ old('appointment_time') }}" required>
                                    @error('appointment_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="service" class="form-label">Servicio solicitado <span class="text-danger">*</span></label>
                                <select class="form-select @error('service') is-invalid @enderror" id="service" name="service" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($services as $service)
                                        <option value="{{$service->id}}" {{ old('service') == $service->id ? 'selected' : '' }}>{{$service->name}}</option>
                                    @endforeach
                                </select>
                                @error('service')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">Motivo de la consulta (opcional)</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="3">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="pet-photo" class="form-label">Subir Foto de la Mascota (opcional)</label>
                                    <input type="file" class="form-control @error('pet_photo') is-invalid @enderror" id="pet-photo" name="pet_photo" accept="image/*">
                                    @error('pet_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="appointment-location" class="form-label">Ubicación para la Cita (Selecciona en el mapa) <span class="text-danger">*</span></label>
                                <div id="map" style="height: 350px; border-radius: 10px; margin-bottom: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);"></div>
                                <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="appointment-location" name="appointment_location" value="{{ old('appointment_location') }}" placeholder="La dirección aparecerá aquí..." readonly required>
                                <input type="hidden" id="latitude" name="latitude">
                                <input type="hidden" id="longitude" name="longitude">
                                @error('latitude')
                                    <div class="invalid-feedback">Por favor, selecciona una ubicación en el mapa.</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input @error('terms') is-invalid @enderror" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">Aceptar <span class="text-danger">*</span></label>
                                @error('terms')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="button" class="btn btn-primary w-100 btn-submit" id="btn-submit" >Solicitar Cita</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonios -->
    <section id="testimonios" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Lo que dicen nuestros clientes</h2>
            <div class="row">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "Llevo a mi perro Max desde que era cachorro. El personal es muy amable y profesional. Siempre recibe la mejor atención."
                        </div>
                        <div class="testimonial-author">- María González</div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "Mi gata Luna tuvo una emergencia y la atendieron inmediatamente. Estoy muy agradecida con el Dr. Cortez y su equipo."
                        </div>
                        <div class="testimonial-author">- Carlos Rodríguez</div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            "Excelente servicio y precios justos. Mis dos perros siempre están saludables gracias a sus cuidados preventivos."
                        </div>
                        <div class="testimonial-author">- Ana Martínez</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contacto -->
    <section id="contacto" class="py-5">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Contáctanos</h2>
            <div class="row">
                <div class="col-md-6" data-aos="fade-right">
                    <h4>Información de contacto</h4>
                    <div class="contact-info">
                        <p><i class="fas fa-map-marker-alt"></i> {{setting('site.address')}}</p>
                        <p><i class="fas fa-phone"></i> +591 {{setting('redes-sociales.whatsapp')}}</p>
                        {{-- <p><i class="fas fa-envelope"></i> {{setting('site.email')??'SN'}}</p> --}}
                        <p><i class="fas fa-clock"></i> Lunes a Viernes: 8:00 am - 6:00 pm</p>
                        <p><i class="fas fa-clock"></i> Sábados: 9:00 am - 2:00 pm</p>
                    </div>
                    <h4>Síguenos en redes sociales</h4>
                    <div class="social-links">
                        @if (setting('redes-sociales.facebook'))
                            <a href="{{setting('redes-sociales.facebook')}}"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if (setting('redes-sociales.instagram'))
                            <a href="{{setting('redes-sociales.instagram')}}"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if (setting('redes-sociales.tiktok'))
                            <a href="{{setting('redes-sociales.tiktok')}}"><i class="fa-brands fa-tiktok"></i></a>                            
                        @endif
                        @if (setting('redes-sociales.twitter'))
                            <a href="{{setting('redes-sociales.twitter')}}"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if (setting('redes-sociales.youtube'))
                           <a href="{{setting('redes-sociales.youtube')}}"><i class="fab fa-youtube"></i></a> 
                        @endif
                        
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                     <div class="map-container" style="border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <div id="contact-map" style="height: 300px;"></div>
                     </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-6">
                    <h4>{{setting('site.title')}}</h4>
                    <p>{{setting('site.description')}}</p>
                </div>
                <div class="col-md-6 mb-6 ">
                    <h4>Enlaces rápidos</h4>
                    <ul class="list-unstyled">
                        <li><a href="#inicio" class="text-light">Inicio</a></li>
                        <li><a href="#servicios" class="text-light">Servicios</a></li>
                        <li><a href="#cita" class="text-light">Solicitar Cita</a></li>
                        <li><a href="#contacto" class="text-light">Contacto</a></li>
                    </ul>
                </div>
                {{-- <div class="col-md-4 mb-4">
                    <h4>Suscríbete a nuestro boletín</h4>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Tu correo electrónico">
                        <button class="btn btn-primary" type="button">Suscribirse</button>
                    </div>
                </div> --}}
            </div>
            <hr class="my-4">
            <div class="text-center">
                {{-- <p>&copy; 2023 {{setting('site.title')}}. Todos los derechos reservados.</p> --}}
                <a style="color: rgb(255, 255, 255); font-size: 15px" href="https://www.soluciondigital.dev/" target="_blank">Copyright <small style="font-size: 15px">SolucionDigital {{date('Y')}}</small>
                    {{-- <br>Todos los derechos reservados. --}}
                </a>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/591{{setting('redes-sociales.whatsapp')}}?text=Hola,%20me%20interesa%20solicitar%20una%20cita%20para%20mi%20mascota" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp whatsapp-icon"></i>
        <span class="whatsapp-text">¡Chatea con nosotros!</span>
    </a>


    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>

        function setCountry(code, flag) {
            document.getElementById('phone-code').innerText = '+' + code;
            document.getElementById('flag-icon').className = 'fi fi-' + flag;
            document.getElementById('country_code').value = code;
        }

        


        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Inicializar AOS (Animate On Scroll)
        AOS.init({
            duration: 800, // Duración de la animación en milisegundos
        });

        // SweetAlert2 Toaster Notifications
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        let showGeoToast = true;

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showGeoToast = false;
                Toast.fire({
                    icon: 'success',
                    title: {!! json_encode(session('success')) !!}
                });
            @endif

            @if($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: 'Por favor, revisa los campos marcados en rojo.'
                });
            @endif
        });

        document.addEventListener('DOMContentLoaded', function () {
            // --- Leaflet Map for Location Picker ---
            const mapboxAccessToken = '{{ setting('system.mapsToken') }}';
            // PRUEBA TEMPORAL: Token puesto directamente para diagnosticar.
            // const mapboxAccessToken = 'pk.eyJ1IjoibmFjaG9kZXZvcyIsImEiOiJjbWlxd3g4bHAwbHZ1M2Rwd2Q4cTd1dzZkIn0.3IMokfY8ZTFfoBJQO35yLw';
            if (!mapboxAccessToken) {
                console.error('Mapbox Access Token no está configurado. El mapa no se puede inicializar.');
                document.getElementById('map').innerHTML = '<div class="alert alert-danger">Error: El mapa no se puede cargar. Contacta al administrador.</div>';
                return;
            }

            // 4. Obtener referencias a los campos del formulario
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const locationInput = document.getElementById('appointment-location');
            locationInput.value = 'Arrastra el marcador o haz clic en el mapa para seleccionar.';

            // 5. Función para actualizar los campos y la dirección
            function updateMarkerPosition(lat, lng) {
                latInput.value = lat;
                lngInput.value = lng;

                // Geocodificación inversa para obtener la dirección (usando Nominatim)
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.display_name) {
                            locationInput.value = data.display_name;
                        } else {
                            locationInput.value = 'No se pudo obtener la dirección.';
                        }
                    })
                    .catch(error => {
                        console.error('Error en geocodificación inversa:', error);
                        locationInput.value = 'Error al obtener la dirección.';
                    });
            }

            function initializeMap(lat, lng) {
                // 1. Initialize the map
                const map = L.map('map', { maxZoom: 18 }).setView([lat, lng], 15);

                // 2. Definir las capas de mapa usando Mapbox
                const mapboxSatellite = L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/satellite-streets-v12/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                    attribution: '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> <strong><a href="https://www.mapbox.com/map-feedback/" target="_blank">Improve this map</a></strong>',
                    tileSize: 512,
                    zoomOffset: -1,
                    accessToken: mapboxAccessToken
                }).addTo(map);

                const mapboxStreets = L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v11/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                    attribution: '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> <strong><a href="https://www.mapbox.com/map-feedback/" target="_blank">Improve this map</a></strong>',
                    tileSize: 512,
                    zoomOffset: -1,
                    accessToken: mapboxAccessToken
                });

                const baseMaps = { "Satélite": mapboxSatellite, "Calles": mapboxStreets };
                L.control.layers(baseMaps).addTo(map);

                // 3. Create a draggable marker at the initial position
                let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

                // 4. Update the initial position
                updateMarkerPosition(lat, lng);

                // 5. Eventos del mapa y marcador
                marker.on('dragend', function(e) {
                    const newPos = e.target.getLatLng();
                    updateMarkerPosition(newPos.lat, newPos.lng);
                });

                map.on('click dblclick', function(e) {
                    const newPos = e.latlng;
                    marker.setLatLng(newPos);
                    updateMarkerPosition(newPos.lat, newPos.lng);
                });
            }

            // --- Lógica de Geolocalización ---
            const defaultLat = -14.8203618; // Default latitude for Trinidad, Bolivia
            const defaultLng = -64.897594;  // Default longitude for Trinidad, Bolivia

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        // Success: User shared their location
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;
                        initializeMap(userLat, userLng);
                        if(showGeoToast) Toast.fire({ icon: 'info', title: 'Mapa centrado en tu ubicación actual.', position: 'bottom-end' });
                    },
                    () => {
                        // Error o denegado: Usar ubicación por defecto
                        initializeMap(defaultLat, defaultLng); // Fallback to default location
                        if(showGeoToast) Toast.fire({ icon: 'warning', title: 'No se pudo obtener tu ubicación. Mostrando ubicación por defecto.', position: 'bottom-end' });
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    }
                );
            } else { // Browser does not support geolocation
                // El navegador no soporta geolocalización
                initializeMap(defaultLat, defaultLng);
                if(showGeoToast) Toast.fire({ icon: 'error', title: 'Tu navegador no soporta geolocalización.' });
            }

            // --- Leaflet Map for Contact Section ---
            const contactLat = -14.8203618;
            const contactLng = -64.897594;

            const contactMap = L.map('contact-map', {
                center: [contactLat, contactLng],
                zoom: 17,
                scrollWheelZoom: false, // Desactiva el zoom con la rueda del ratón
                dragging: !L.Browser.mobile, // Activa el arrastre solo en escritorio
                tap: L.Browser.mobile, // Activa el tap en móviles
                zoomControl: true // Muestra los controles de zoom
            });

            L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/satellite-streets-v12/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: '© Mapbox',
                tileSize: 512,
                zoomOffset: -1,
                accessToken: mapboxAccessToken
            }).addTo(contactMap);

            L.marker([contactLat, contactLng]).addTo(contactMap)
                .bindPopup('<b>{{setting("site.title")}}</b><br>{{setting("site.address")}}')
                .openPopup();

            // --- Dynamic Race Loading Logic ---
            const petTypeSelect = document.getElementById('pet-type');
            const oldPetType = @json(old('pet_type'));
            const oldPetRace = @json(old('pet_race'));
            const raceSelect = document.getElementById('pet_race');

            // Limpiar y deshabilitar el select de razas mientras se carga
            raceSelect.innerHTML = '<option value="" selected disabled>Cargando razas...</option>';
            raceSelect.disabled = true;

            // Function to load races based on animalId and optionally select a race
            function loadRaces(animalId, selectedRaceId = null) {
                raceSelect.innerHTML = '<option value="" selected disabled>Cargando razas...</option>';
                raceSelect.disabled = true;

                if (animalId) {
                    fetch(`{{ url('/api/races') }}/${animalId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('La respuesta de la red no fue exitosa');
                            }
                            return response.json();
                        })
                        .then(races => {
                            raceSelect.innerHTML = '<option value="" selected disabled>Seleccione una raza</option>';
                            if (races.length > 0) {
                                races.forEach(race => {
                                    const option = document.createElement('option');
                                    option.value = race.id;
                                    option.textContent = race.name;
                                    if (selectedRaceId && String(race.id) === String(selectedRaceId)) { // Ensure string comparison
                                        option.selected = true;
                                    }
                                    raceSelect.appendChild(option);
                                });

                                const otherOption = document.createElement('option');
                                otherOption.value = "0"; // Consistent value for "Otras"
                                otherOption.textContent = "Otras";
                                if (selectedRaceId && String(selectedRaceId) === "0") {
                                    otherOption.selected = true;
                                }
                                raceSelect.appendChild(otherOption);

                                raceSelect.disabled = false;
                            } else {
                                raceSelect.innerHTML = '<option value="" selected disabled>No hay razas específicas para esta especie</option>';
                                const otherOption = document.createElement('option');
                                otherOption.value = "0";
                                otherOption.textContent = "Otras";
                                if (selectedRaceId && String(selectedRaceId) === "0") {
                                    otherOption.selected = true;
                                }
                                raceSelect.appendChild(otherOption);
                                raceSelect.disabled = false;
                            }
                        })
                        .catch(error => {
                            console.error('Error al cargar las razas:', error);
                            raceSelect.innerHTML = '<option value="" selected disabled>Error al cargar razas</option>';
                        });
                }
            }

            // Event listener for pet-type change
            petTypeSelect.addEventListener('change', function() {
                loadRaces(this.value);
            });

            // On page load, if old('pet_type') exists, load races and try to select old('pet_race')
            if (oldPetType) {
                loadRaces(oldPetType, oldPetRace);
            }

            // --- Lógica para limpiar errores de validación al interactuar con los campos ---
            const formInputs = document.querySelectorAll('#appointment-form .form-control, #appointment-form .form-select, #appointment-form .form-check-input');

            formInputs.forEach(input => {
                // Escuchar eventos 'input' para campos de texto/textarea y 'change' para selects/checkboxes
                const eventType = (input.tagName.toLowerCase() === 'select' || input.type === 'checkbox' || input.type === 'radio') ? 'change' : 'input';

                input.addEventListener(eventType, function() {
                    // Si el campo tiene la clase de error 'is-invalid', la removemos
                    if (this.classList.contains('is-invalid')) {
                        this.classList.remove('is-invalid');

                        // Busca el contenedor padre más cercano que también contiene el mensaje de error.
                        // Usamos .closest('.col-md-6, .mb-3') para cubrir tanto los campos en columnas como los de ancho completo.
                        const parentContainer = this.closest('.col-md-6') || this.closest('.mb-3');
                        if (parentContainer) {
                            const feedback = parentContainer.querySelector('.invalid-feedback');
                            if (feedback) {
                                // Ocultamos el mensaje de error directamente.
                                feedback.style.display = 'none'; // Oculta el elemento
                                feedback.classList.remove('d-block'); // Elimina la clase que lo fuerza a mostrarse
                            }
                        }
                    }
                });
            });
        });


        // $(document).ready(function() {
        //     $('#appointment-form1').submit(function(e) {
        //         $('.btn-submit').html('Enviando... <i class="fa fa-spinner fa-spin"></i>');
        //         $('.btn-submit').attr('disabled', true);
        //     });
        // });

    </script>
</body>
</html>
