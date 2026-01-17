@section('footer')
<footer class="bg-dark text-light mt-auto">
    <div class="container py-4">
        <div class="row align-items-start text-center text-lg-start g-4">

            {{-- SINISTRA --}}
            <div class="col-12 col-lg-4">
                <div class="small">
                    <div>Via Monsampietro, 68 - 63091 Ascoli Piceno (AP)</div>
                    <div>Tel. +39 340 561 8282</div>
                    <div>
                        <a href="mailto:info@cantinabellò.com"
                           class="text-light text-decoration-none">
                            info@cantinabellò.com
                        </a>
                    </div>
                </div>
            </div>

            {{-- CENTRO --}}
            <div class="col-12 col-lg-4">
                <div class="small">
                    <div style="text-align: center;">© Azienda Vinicola Bellò Spa</div>
                    <div style="text-align: center;">
                        P.iva 00000000000   
                    </div>
                </div>
            </div>

            {{-- DESTRA --}}
            <div class="col-12 col-lg-4">
                <h5 style="text-align: end;">Seguici sui social</h5>

                <div class="d-flex justify-content-center justify-content-lg-end gap-2 mt-3">
                    <a href="https://www.facebook.com/matthias.zoldi/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-outline-light rounded-circle d-flex align-items-center justify-content-center"
                        style="width:42px;height:42px">
                            <i class="bi bi-facebook"></i>
                    </a>

                        {{-- Instagram --}}
                    <a href="https://www.instagram.com/matthias_zoldi"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-outline-light rounded-circle d-flex align-items-center justify-content-center"
                        style="width:42px;height:42px">
                            <i class="bi bi-instagram"></i>
                     </a>
                </div>

                
            </div>
        </div>

    </div>
</footer>


@endsection