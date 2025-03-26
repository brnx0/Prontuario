
    <div class="modal fade" id="enfModal" tabindex="-1" aria-labelledby="enfModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enfModalLabel">Cadastro de Enfermeiros</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" id="formEnf">
                        @csrf
                        <input type="text" hidden id="enf_cod" name="enf_cod">
                        <div class="row mb-4">
                            <div class="col-md-7">
                                <label for="nomeEnf" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nomeEnf" name="nomeEnf" required placeholder="Digite o nome do médico">
                            </div>
                            <div class="col-md-5">
                                <label for="creEnf" class="form-label">CRE</label>
                                <input type="text" class="form-control" id="creEnf" name="creEnf" placeholder="Digite o CRE">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



