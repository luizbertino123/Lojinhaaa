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
                        <div class="card-header d-block">
                            <h4><?php echo $titulo; ?></h4>
                            <a title="Cadastrar usuário" class="btn btn-primary float-right" href="<?php echo base_url('restrita/clientes/core'); ?>"><i class="fa fa-plus">&nbsp;</i>Cadastrar</a>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-striped data-table">
                                    <thead>
                                        <tr>
                                            <th>
                                                #
                                            </th>
                                            <th class="text-center">Nome completo</th>
                                            <th class="text-center">Data nascimento</th>
                                            <th class="text-center">CPF</th>
                                            <th class="text-center">Celular</th>                                            
                                            <th class="nosort text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($clientes as $cliente): ?>
                                            <tr>
                                                <td class="text-center"><?php echo $cliente->cliente_id; ?></td>
                                                <td class="text-center"><?php echo $cliente->cliente_nome . '' . $cliente->cliente_sobrenome; ?></td>
                                                <td class="text-center"><?php echo formata_data_banco_sem_hora($cliente->cliente_data_nascimento); ?></td>
                                                <td class="text-center"><?php echo $cliente->cliente_cpf; ?></td>
                                                <td class="text-center"><?php echo $cliente->cliente_telefone_movel; ?></td>


                                                <td class="text-center">
                                                    <a title="Editar usuário" href="<?php echo base_url('restrita/clientes/core/' . $cliente->cliente_id); ?>" class="btn btn-primary"><i class="fa fa-edit"></i>&nbsp;&nbsp;Editar</a>&nbsp;&nbsp;
                                                    <a title="Excluir usuário" href="<?php echo base_url('restrita/clientes/delete/' . $cliente->cliente_id); ?>" class="btn btn-danger delete" data-confirm="Tem certeza da exclusão?"><i class="fa fa-trash">&nbsp;&nbsp;</i>Excluir</a>
                                                </td>                                          
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <?php $this->load->view('restrita/layout/sidebar_settings'); ?>



</div>