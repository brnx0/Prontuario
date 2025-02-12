

    <form  method="POST" id="PacienteForm">
        @csrf
        
        <div class="modal fade" id="pacienteModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Cadastro de Paciente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formPaciente">
                            <input type="text" name="pes_cod" hidden>
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <label for="nome" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="nome" name='NOME' required>
                                </div>
                                <div class="col-md-3">
                                    <label for="profissao" class="form-label">Profissão</label>
                                    <select class="form-select" id="profissao" >
                                        <option value="">Selecione</option>
                                        <option value="Medico">Médico</option>
                                        <option value="Enfermeiro">Enfermeiro</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="idade" class="form-label">Nascimento</label>
                                    <input type="date" class="form-control" id="nascimento" name="NASCIMENTO" onblur="calcularIdade(this.value,'idade')">
                                </div>
                                <div class="col-md-1">
                                    <label for="idade" class="form-label">Idade</label>
                                    <input type="tex" class="form-control" id="idade" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="filiacao1" class="form-label">Filiação 1</label>
                                    <input type="text" class="form-control" id="filiacao_1" name="FILICAO_1">
                                </div>
                                <div class="col-md-6">
                                    <label for="filiacao2" class="form-label">Filiação 2</label>
                                    <input type="text" class="form-control" id="filiacao_2" name="FILICAO_2">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" class="form-control" id="cep" name="CEP">
                                </div>
                                <div class="col-md-5">
                                    <label for="logradouro" class="form-label">Logradouro</label>
                                    <input type="text" class="form-control" id="logradouro" name="LOGRADOURO">
                                </div>
                                <div class="col-md-3">
                                    <label for="cidade" class="form-label">Cidade</label>
                                    <input type="text" class="form-control" id="cidade" name="CIDADE">
                                </div>
                                <div class="col-md-1">
                                    <label for="uf" class="form-label">UF</label>
                                    <input type="text" class="form-control" id="uf" name="UF">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="text" class="form-control" id="telefone" name="TEL_1">
                                </div>
                                <div class="col-md-4">
                                    <label for="celular" class="form-label">Celular</label>
                                    <input type="text" class="form-control" id="celular" name="TEL_2">
                                </div>
                                <div class="col-md-4">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="EMAIL">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="cartaoSUS" class="form-label">Cartão do SUS</label>
                                    <input type="text" class="form-control" id="cartaoSUS" name="CARTAO_SUS">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </form> 