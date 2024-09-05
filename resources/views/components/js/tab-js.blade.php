<script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-button');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active', 'bg-gray-300'));
                    contents.forEach(c => c.classList.add('hidden'));

                    tab.classList.add('active', 'bg-gray-300');
                    const contentId = `tab-content-${tab.id.split('-')[1]}`;
                    document.getElementById(contentId).classList.remove('hidden');
                });
            });
        });
</script>