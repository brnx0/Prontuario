
    <div class="modal fade" id="especialidadeModal" tabindex="-1" aria-labelledby="especialidadeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="especialidadeModalLabel">Cadastro de Especialidades</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label for="descEspc" class="form-label">Especialidade</label>
                                <input type="text" class="form-control" id="descEspc" name="descEspc" required placeholder="Informe a especialidade">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



