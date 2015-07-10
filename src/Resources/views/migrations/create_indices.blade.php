<?php echo '<?php' ?>

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indices', function(Blueprint $table) {

            $table->increments('id');
            $table->text('params');
            $table->integer('order')->unsigned()->default(0);
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->string('meta', 255);
            $table->string('namespace', 255);
            $table->text('href');
            $table->integer('disabled')->default(0);
            $table->integer('navigation')->default(0);
            $table->integer('published')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('indices');
    }
}
