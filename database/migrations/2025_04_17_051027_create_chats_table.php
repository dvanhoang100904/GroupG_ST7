    <?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('chats', function (Blueprint $table) {
                $table->bigIncrements('chat_id'); 
                $table->text('description')->nullable();
                $table->unsignedBigInteger('assessment_star_id')->nullable();
                $table->string('photo', 255)->nullable();
                $table->unsignedBigInteger('user_id'); 
                $table->unsignedBigInteger('receiver_id'); // người nhận chat ví dụ khách hàng nhận cho admin
                $table->string('type')->default('chat'); // phân loại trên cùng 1 bảng
                $table->string('status')->default('open'); // tắt hoặc bật
                $table->timestamps();
            });
    }
        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('chats');
        }
    };
