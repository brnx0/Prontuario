

    <div class="modal fade" id="medicoModal" tabindex="-1" aria-labelledby="medicoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="medicoModalLabel">Cadastro de Médico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-7">
                                <label for="nomeMedico" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nomeMedico" name="nomeMedico" required placeholder="Digite o nome do médico">
                            </div>
                            <div class="col-md-5">
                                <label for="crmMedico" class="form-label">CRM</label>
                                <input type="text" class="form-control" id="crmMedico" name="crmMedico" placeholder="Digite o CRM">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



