<?php $this->load->view('restrita/layout/navibar'); ?>

<?php $this->load->view('restrita/layout/sidebar'); ?>




<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <!-- add content here -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">                         
                            <h4><?php echo $titulo; ?></h4>                       
                        </div>                       

                        <?php
                        $atributos = array(
                            'name' => 'form_core',
                        );

                        if (isset($cliente)) {
                            $cliente_id = $cliente->cliente_id;
                        } else {
                            $cliente_id = '';
                        }
                        ?>

                        <?php echo form_open('/restrita/clientes/core/' . $cliente_id, $atributos); ?>

                        <div class="card-body">

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Nome</label>
                                    <input type="text" name="cliente_nome" class="form-control" placeholder="Nome" value="<?php echo (isset($cliente) ? $cliente->cliente_nome : set_value('cliente_nome')); ?>">
                                    <?php echo form_error('cliente_nome', '<div class="text-danger">', '</div>'); ?>
                                </div>        

                                <div class="form-group col-md-3">
                                    <label>Sobrenome</label>
                                    <input type="text" name="cliente_sobrenome" class="form-control"  placeholder="Sobrenome" value="<?php echo (isset($cliente) ? $cliente->cliente_sobrenome : set_value('cliente_sobrenome')); ?>">
                                    <?php echo form_error('cliente_sobrenome', '<div class="text-danger">', '</div>'); ?>
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Data nascimento</label>
                                    <input type="date" name="cliente_data_nascimento" class="form-control"  placeholder="Sobrenome" value="<?php echo (isset($cliente) ? $cliente->cliente_data_nascimento : set_value('cliente_data_nascimento')); ?>">
                                    <?php echo form_error('cliente_data_nascimento', '<div class="text-danger">', '</div>'); ?>
                                </div>

                                <div class="form-group col-md-3">
                                    <label>CPF</label>
                                    <input type="text" name="cliente_cpf" class="form-control cpf" value="<?php echo (isset($cliente) ? $cliente->cliente_cpf : set_value('cliente_cpf')); ?>">
                                    <?php echo form_error('cliente_cpf', '<div class="text-danger">', '</div>'); ?>
                                </div>


                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Celular</label>
                                    <input type="text" name="cliente_telefone_movel" class="form-control sp_celphones" value="<?php echo (isset($cliente) ? $cliente->cliente_telefone_movel : set_value('cliente_telefone_movel')); ?>">
                                    <?php echo form_error('cliente_telefone_movel', '<div class="text-danger">', '</div>'); ?>
                                </div> 

                                <div class="form-group col-md-4">
                                    <label>Telefone fixo</label>
                                    <input type="text" name="cliente_telefone_fixo" class="form-control sp_celphones" value="<?php echo (isset($cliente) ? $cliente->cliente_telefone_fixo : set_value('cliente_telefone_fixo')); ?>">
                                    <?php echo form_error('cliente_telefone_fixo', '<div class="text-danger">', '</div>'); ?>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>E-mail</label>
                                    <input type="email" name="cliente_email" class="form-control" value="<?php echo (isset($cliente) ? $cliente->cliente_email : set_value('cliente_email')); ?>">
                                    <?php echo form_error('cliente_email', '<div class="text-danger">', '</div>'); ?>
                                </div>


                            </div> 

                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label>CEP</label>
                                    <input type="text" name="cliente_cep" class="form-control cep" value="<?php echo (isset($cliente) ? $cliente->cliente_cep : set_value('cliente_cep')); ?>">
                                    <?php echo form_error('cliente_cep', '<div class="text-danger">', '</div>'); ?>
                                </div> 

                                <div class="form-group col-md-4">
                                    <label>Endereço</label>
                                    <input type="text" name="cliente_endereco" class="form-control" value="<?php echo (isset($cliente) ? $cliente->cliente_endereco : set_value('cliente_endereco')); ?>">
                                    <?php echo form_error('cliente_endereco', '<div class="text-danger">', '</div>'); ?>
                                </div> 

                                <div class="form-group col-md-3">
                                    <label>Número</label>
                                    <input type="text" name="cliente_numero_endereco" class="form-control" value="<?php echo (isset($cliente) ? $cliente->cliente_numero_endereco : set_value('cliente_numero_endereco')); ?>">
                                    <?php echo form_error('cliente_numero_endereco', '<div class="text-danger">', '</div>'); ?>
                                </div> 

                                <div class="form-group col-md-3">
                                    <label>Bairro</label>
                                    <input type="text" name="cliente_bairro" class="form-control" value="<?php echo (isset($cliente) ? $cliente->cliente_bairro : set_value('cliente_bairro')); ?>">
                                    <?php echo form_error('cliente_bairro', '<div class="text-danger">', '</div>'); ?>
                                </div>

                            </div> 

                            <div class="form-row">

                                <div class="form-group col-md-3">
                                    <label>Cidade</label>
                                    <input type="text" name="cliente_cidade" class="form-control" value="<?php echo (isset($cliente) ? $cliente->cliente_cidade : set_value('cliente_cidade')); ?>">
                                    <?php echo form_error('cliente_cidade', '<div class="text-danger">', '</div>'); ?>
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Estado</label>


                                    <?php if ($cliente): ?>


                                        <select class="custom-select" name="cliente_estado">
                                            <option value="">Escolha o estado</option
                                            <option value="AC" <?php echo ($cliente->cliente_estado == 'AC' ? 'selected' : ''); ?>>Acre</option>
                                            <option value="AL" <?php echo ($cliente->cliente_estado == 'AL' ? 'selected' : ''); ?>>Alagoas</option>
                                            <option value="AP" <?php echo ($cliente->cliente_estado == 'AP' ? 'selected' : ''); ?>>Amapá</option>
                                            <option value="AM" <?php echo ($cliente->cliente_estado == 'AM' ? 'selected' : ''); ?>>Amazonas</option>
                                            <option value="BA" <?php echo ($cliente->cliente_estado == 'BA' ? 'selected' : ''); ?>>Bahia</option>
                                            <option value="CE" <?php echo ($cliente->cliente_estado == 'CE' ? 'selected' : ''); ?>>Ceará</option>
                                            <option value="DF" <?php echo ($cliente->cliente_estado == 'DF' ? 'selected' : ''); ?>>Distrito Federal</option>
                                            <option value="ES" <?php echo ($cliente->cliente_estado == 'ES' ? 'selected' : ''); ?>>Espírito Santo</option>
                                            <option value="GO" <?php echo ($cliente->cliente_estado == 'GO' ? 'selected' : ''); ?>>Goiás</option>
                                            <option value="MA" <?php echo ($cliente->cliente_estado == 'MA' ? 'selected' : ''); ?>>Maranhão</option>
                                            <option value="MT" <?php echo ($cliente->cliente_estado == 'MT' ? 'selected' : ''); ?>>Mato Grosso</option>
                                            <option value="MS" <?php echo ($cliente->cliente_estado == 'MS' ? 'selected' : ''); ?>>Mato Grosso do Sul</option>
                                            <option value="MG" <?php echo ($cliente->cliente_estado == 'MG' ? 'selected' : ''); ?>>Minas Gerais</option>
                                            <option value="PA" <?php echo ($cliente->cliente_estado == 'PA' ? 'selected' : ''); ?>>Pará</option>
                                            <option value="PB" <?php echo ($cliente->cliente_estado == 'PB' ? 'selected' : ''); ?>>Paraíba</option>
                                            <option value="PR" <?php echo ($cliente->cliente_estado == 'PR' ? 'selected' : ''); ?>>Paraná</option>
                                            <option value="PE" <?php echo ($cliente->cliente_estado == 'PE' ? 'selected' : ''); ?>>Pernambuco</option>
                                            <option value="PI" <?php echo ($cliente->cliente_estado == 'PI' ? 'selected' : ''); ?>>Piauí</option>
                                            <option value="RJ" <?php echo ($cliente->cliente_estado == 'RJ' ? 'selected' : ''); ?>>Rio de Janeiro</option>
                                            <option value="RN" <?php echo ($cliente->cliente_estado == 'RN' ? 'selected' : ''); ?>>Rio Grande do Norte</option>
                                            <option value="RS" <?php echo ($cliente->cliente_estado == 'RS' ? 'selected' : ''); ?>>Rio Grande do Sul</option>
                                            <option value="RO" <?php echo ($cliente->cliente_estado == 'RO' ? 'selected' : ''); ?>>Rondônia</option>
                                            <option value="RR" <?php echo ($cliente->cliente_estado == 'RR' ? 'selected' : ''); ?>>Roraima</option>
                                            <option value="SC" <?php echo ($cliente->cliente_estado == 'SC' ? 'selected' : ''); ?>>Santa Catarina</option>
                                            <option value="SP" <?php echo ($cliente->cliente_estado == 'SP' ? 'selected' : ''); ?>>São Paulo</option>
                                            <option value="SE" <?php echo ($cliente->cliente_estado == 'SE' ? 'selected' : ''); ?>>Sergipe</option>
                                            <option value="TO" <?php echo ($cliente->cliente_estado == 'TO' ? 'selected' : ''); ?>>Tocantins</option>
                                        </select>

                                    <?php else: ?>

                                        <select class="custom-select" name="cliente_estado">
                                            <select name="estados-brasil">
                                                <option value="AC">Acre</option>
                                                <option value="AL">Alagoas</option>
                                                <option value="AP">Amapá</option>
                                                <option value="AM">Amazonas</option>
                                                <option value="BA">Bahia</option>
                                                <option value="CE">Ceará</option>
                                                <option value="DF">Distrito Federal</option>
                                                <option value="ES">Espírito Santo</option>
                                                <option value="GO">Goiás</option>
                                                <option value="MA">Maranhão</option>
                                                <option value="MT">Mato Grosso</option>
                                                <option value="MS">Mato Grosso do Sul</option>
                                                <option value="MG">Minas Gerais</option>
                                                <option value="PA">Pará</option>
                                                <option value="PB">Paraíba</option>
                                                <option value="PR">Paraná</option>
                                                <option value="PE">Pernambuco</option>
                                                <option value="PI">Piauí</option>
                                                <option value="RJ">Rio de Janeiro</option>
                                                <option value="RN">Rio Grande do Norte</option>
                                                <option value="RS">Rio Grande do Sul</option>
                                                <option value="RO">Rondônia</option>
                                                <option value="RR">Roraima</option>
                                                <option value="SC">Santa Catarina</option>
                                                <option value="SP">São Paulo</option>
                                                <option value="SE">Sergipe</option>
                                                <option value="TO">Tocantins</option>
                                            </select>



                                        <?php endif; ?>                                   



                                        <?php echo form_error('cliente_estado', '<div class="text-danger">', '</div>'); ?>
                                </div>


                                <div class="form-group col-md-6">
                                    <label>Complemento</label>
                                    <input type="text" name="cliente_complemento" class="form-control" value="<?php echo (isset($cliente) ? $cliente->cliente_complemento : set_value('cliente_complemento')); ?>">
                                    <?php echo form_error('cliente_complemento', '<div class="text-danger">', '</div>'); ?>
                                </div>

                            </div>   

                            <div class="form-row">

                                <div class="form-group col-md-3">
                                    <label>Senha</label>
                                    <input type="password" name="password" class="form-control">
                                    <?php echo form_error('password', '<div class="text-danger">', '</div>'); ?>
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Confirmar</label>
                                    <input type="password" name="confirma" class="form-control">
                                    <?php echo form_error('confirma', '<div class="text-danger">', '</div>'); ?>
                                </div>

                            </div>

                            <?php if (isset($cliente)): ?>
                                <input type="hidden" name="cliente_id" value="<?php echo $cliente->cliente_id ?>">
                            <?php endif; ?>


                        </div>                
                        <div class="card-footer">
                            <button class="btn btn-primary mr-2">Salvar</button>                      
                            <a class="btn btn-dark" href="<?php echo base_url('restrita/usuarios'); ?>">Voltar</a>                            
                        </div>

                        <?php echo form_close(); ?>

                    </div>
                </div>
            </div>

        </div>
    </section>

    <?php $this->load->view('restrita/layout/sidebar_settings'); ?>
</div>