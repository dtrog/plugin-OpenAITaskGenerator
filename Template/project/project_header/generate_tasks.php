<div class="modal fade" id="generate-tasks-modal" tabindex="-1" role="dialog" aria-labelledby="generateTasksModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="generateTasksModalLabel"><?= t('Generate Tasks from Goal') ?></h>
            </div>
            <form action="<?= $this->url->href('TaskGeneratorController', 'generateTasks', ['plugin' => 'OpenAITaskGenerator', 'project_id' => $project_id]) ?>" method="post">
                <div class="modal-body">
                    <?= $this->form->csrf() ?>
                    <label for="goal"><?= t('Enter your project goal') ?></label>
                    <textarea name="goal" id="goal" required><?= isset($goal) ? $this->text->e($goal) : '' ?></textarea>
                </div>
                <?= $this->modal->submitButtons(array('submitLabel' => t('Generate') )) ?>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Automatically initialize Bootstrap modals
        $('#generate-tasks-modal').modal({
            show: false // Only show when triggered
        });
    });
</script>
